<?php

namespace Webjump\PathHint\Plugin\Model\TemplateEngine;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\TemplateEngineFactory;
use Magento\Framework\View\TemplateEngineInterface;
use Magento\Store\Model\ScopeInterface;

class DebugHints extends \Magento\Developer\Model\TemplateEngine\Plugin\DebugHints {
    
    public function afterCreate(
        TemplateEngineFactory $subject,
        TemplateEngineInterface $invocationResult
    ) {
        $params = ObjectManager::getInstance()->get('Magento\Framework\App\RequestInterface')->getParams();
        
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
