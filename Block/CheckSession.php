<?php
/**
 * @author Robert Rupa <office@konatsu.pl>
 */

namespace RobertRupa\AutomaticLogout\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Session\Config;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Customer\Model\Session as CustomerSession;

class CheckSession extends Template
{

    const COOKIE_NAME = 'session_lifetime';
    const REDIRECT_URL_PATH = 'web/cookie/session_timeout_redirect_url';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;
    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;
    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * CheckSession constructor.
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param CustomerSession $customerSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        CustomerSession $customerSession,
        array $data = [])
    {
        parent::__construct(
            $context,
            $data
        );
        $this->scopeConfig = $scopeConfig;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->customerSession = $customerSession;
    }
    
    /**
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function addSessionLifeTimeCookie(){
        $lifeTime = $this->getSessionLifeTime();

        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration($lifeTime)
            ->setPath('/')
            ->setDomain('/');

        $this->cookieManager->setPublicCookie(
            self::COOKIE_NAME,
            (int)$this->customerSession->isLoggedIn(),
            $metadata
        );

    }

    /**
     * @return string
     */
    public function getRedirectUrl() : string
    {
        return $this->scopeConfig->getValue(self::REDIRECT_URL_PATH,ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return int
     */
    private function getSessionLifeTime() : int
    {
        return ((int)$this->scopeConfig->getValue(Config::XML_PATH_COOKIE_LIFETIME,ScopeInterface::SCOPE_STORE));
    }
}
