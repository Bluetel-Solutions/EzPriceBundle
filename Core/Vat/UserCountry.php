<?php

namespace EzSystems\EzPriceBundle\Core\Vat;

use EzSystems\EzPriceBundle\API\Vat\UserCountry as UserCountryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Service used to find out what the users current country is.
 */
class UserCountry implements UserCountryInterface
{
    /**
     * Session.
     *
     * @var Session
     */
    protected $session;

    /**
     * The country that we should default to if the users country cannot
     * be found. Needs to be an Alpha2 code for the country.
     *
     * @var string
     */
    protected $defaultUserCountry;

    /**
     * The name of the session variable that contains the users country.
     *
     * @var string
     */
    protected $countrySessionVariableName;

    /**
     * __construct.
     *
     * @param Session $session
     * @param string  $defaultUserCountry
     * @param string  $countrySessionVariableName
     */
    public function __construct(
        Session $session,
        $defaultUserCountry,
        $countrySessionVariableName = 'UserPreferredCountry'
    ) {
        $this->session = $session;
        $this->defaultUserCountry = $defaultUserCountry;
        $this->countrySessionVariableName = $countrySessionVariableName;
    }

    /**
     * Used to fetch the currect users country from the session, If it cannot
     * be found the the default country will be returned.
     *
     * @return string
     */
    public function fetchUsersCountry()
    {
        if ($this->session->has($this->countrySessionVariableName)) {
            // Fetch the users country from their session
            return $this->session->get($this->countrySessionVariableName);
        } else {
            // Get default user country
            return $this->defaultUserCountry;
        }
    }
}
