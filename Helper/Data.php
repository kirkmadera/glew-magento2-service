<?php
namespace Glew\Service\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {
    
    protected $config;
    protected $store;
    protected $context;
    protected $storeManager;
    protected $scopeConfig;
    protected $logger;
    protected $moduleList;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     */
     public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Module\ModuleListInterface $moduleList
     ) {
         parent::__construct($context);
         $this->context = $context;
         $this->storeManager = $storeManager;
         $this->scopeConfig = $scopeConfig;
         $this->logger = $logger;
         $this->moduleList = $moduleList;
     }

     public function getBaseDir() {
         return $this->context->getBaseDir().'/app/code/community/Glew/';
     }

     public function getConfig() {
         $config = array();
         $config['enabled'] = $this->scopeConfig->getValue('glew_settings/general/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
         $config['security_token'] = $this->scopeConfig->getValue('glew_settings/general/security_token', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
         $this->config = $config;
         return $config;
     }

     public function getVersion() {
         return $this->moduleList->getOne('Glew_Service')['setup_version'];
     }

     public function formatDate($str) {
         if($str) {
             if(stripos($str, ' ')) {
                 $str = substr($str, 0, stripos($str, ' '));
             }
         }

         return $str;
     }

     public function toArray($value, $create = false) {
         if($value !== false) {
             return is_array($value) ? $value : array($value);
         } else {
             return $create ? array() : $value;
         }
     }

     public function logException($ex, $msg) {
         $this->logger->debug($ex);
         return false;
     }

     public function log($msg) {
         return $this->logger->info($msg);
     }

     public function getStore() {
         if($this->store == null) {
             $this->store = $this->storeManager->getStore();
         }

         return $this->store;
     }

     public function getStores() {
         return $this->storeManager->getStores();
     }

     public function paginate($array, $pageNumber, $pageSize) {
         $start = $pageNumber * $pageSize;
         return array_slice($array, $start, $pageSize);
     }
 }