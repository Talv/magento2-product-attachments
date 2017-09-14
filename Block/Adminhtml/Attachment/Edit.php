<?php

namespace Talv\ProductAttachments\Block\Adminhtml\Attachment;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
	public $coreRegistry;

    /**
     * constructor
     *
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {

        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize Attachment edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'attachment_id';
        $this->_blockGroup = 'Talv_ProductAttachments';
        $this->_controller = 'adminhtml_attachment';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save'));
        $this->buttonList->add(
            'save-and-continue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ]
                    ]
                ]
            ],
            -100
        );
        $this->buttonList->update('delete', 'label', __('Delete'));
    }

    /**
     * Retrieve text for header element depending on loaded Attachment
     *
     * @return string
     */
    public function getHeaderText()
    {
        /** @var \Talv\ProductAttachments\Model\Attachment $attachment */
        $attachment = $this->coreRegistry->registry('talv_attachments_attachment');
        if ($attachment->getId()) {
            return __("Edit Attachment '%1'", $this->escapeHtml($attachment->getName()));
        }
        return __('New Attachment');
    }
}
