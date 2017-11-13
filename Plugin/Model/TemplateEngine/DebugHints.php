<?php

namespace LucasCalazans\PathHint\Plugin\Model\TemplateEngine;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Developer\Helper\Data as DevHelper;
use Magento\Framework\View\TemplateEngineFactory;
use Magento\Framework\View\TemplateEngineInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Developer\Model\TemplateEngine\Plugin\DebugHints as DeveloperDebugHints;
use Magento\Developer\Model\TemplateEngine\Decorator\DebugHintsFactory;

class DebugHints extends DeveloperDebugHints {

    protected $_requestInterface;

    /**
     * @param RequestInterface $requestInterface
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param DevHelper $devHelper
     * @param DebugHintsFactory $debugHintsFactory
     * @param string $debugHintsPath
     */
    public function __construct(
        RequestInterface $requestInterface,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        DevHelper $devHelper,
        DebugHintsFactory $debugHintsFactory,
        $debugHintsPath
    ) {
        $this->_requestInterface = $requestInterface;
        parent::__construct($scopeConfig, $storeManager, $devHelper, $debugHintsFactory, $debugHintsPath);
    }

    public function afterCreate(
        TemplateEngineFactory $subject,
        TemplateEngineInterface $invocationResult
    ) {
        $params = $this->_requestInterface->getParams();

        $storeCode = $this->storeManager->getStore()->getCode();
        if (isset($params['enablepath']) ||
            ($this->scopeConfig->getValue(
            $this->debugHintsPath, ScopeInterface::SCOPE_STORE, $storeCode) &&
            $this->devHelper->isDevAllowed())) {

            $showBlockHints = ((isset($params['enablepath']) && $params['enablepath'] == 1) || $this->scopeConfig->getValue(
                self::XML_PATH_DEBUG_TEMPLATE_HINTS_BLOCKS,
                ScopeInterface::SCOPE_STORE,
                $storeCode
            ));
            return $this->debugHintsFactory->create([
                'subject' => $invocationResult,
                'showBlockHints' => $showBlockHints,
            ]);
        }
        return $invocationResult;
    }
}
