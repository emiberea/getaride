<?php

namespace EB\UserBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use EB\UserBundle\Entity\User;

class EBUserProvider extends BaseClass
{
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        /** @var User $user */
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));

        // when the user is registering
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            switch ($service) {
                case 'facebook':
                    $user = $this->createFacebookUser($response);
                    break;
                case 'google':
                    $user = $this->createGoogleUser($response);
                    break;
                default:
                    throw new NotFoundHttpException();
                    break;
            }

            return $user;
        }

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }

    /**
     * @param UserResponseInterface $response
     * @return User
     */
    private function createFacebookUser(UserResponseInterface $response)
    {
        $service = $response->getResourceOwner()->getName();
        $username = $response->getUsername();

        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        /** @var User $user */
        $user = $this->userManager->findUserBy(array(
            'email' => $response->getEmail(),
        ));
        if ($user == null) {
            // create new user
            /** @var User $user */
            $user = $this->userManager->createUser();
        }
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        // set user fields
        $user->setUsername($response->getResponse()['username']);
        $user->setEmail($response->getEmail());
        $user->setFirstname($response->getResponse()['first_name']);
        $user->setLastname($response->getResponse()['last_name']);
        $user->setGender($response->getResponse()['gender']);
        $user->setFacebookProfileLink($response->getResponse()['link']);
        $user->setFacebookPictureLink('https://graph.facebook.com/' . $response->getResponse()['username'] . '/picture?type=large');

        $user->setPlainPassword(mt_rand(100000, 999999));
        $user->setEnabled(true);

        $this->userManager->updateUser($user);

        return $user;
    }

    /**
     * @param UserResponseInterface $response
     * @return User
     */
    private function createGoogleUser(UserResponseInterface $response)
    {
        $service = $response->getResourceOwner()->getName();
        $username = $response->getUsername();

        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        /** @var User $user */
        $user = $this->userManager->findUserBy(array(
            'email' => $response->getEmail(),
        ));
        if ($user == null) {
            // create new user
            /** @var User $user */
            $user = $this->userManager->createUser();
        }
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        // set user fields
        $user->setUsername(substr($response->getEmail(), 0, strpos($response->getEmail(), '@')));
        $user->setEmail($response->getEmail());
        $user->setFirstname($response->getResponse()['given_name']);
        $user->setLastname($response->getResponse()['family_name']);
        $user->setGender($response->getResponse()['gender']);
        $user->setGoogleProfileLink($response->getResponse()['link']);
        $user->setGooglePictureLink($response->getResponse()['picture']);

        $user->setPlainPassword(mt_rand(100000, 999999));
        $user->setEnabled(true);

        $this->userManager->updateUser($user);

        return $user;
    }
}
