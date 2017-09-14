<?php

namespace Talv\ProductAttachments\Block\Adminhtml\Attachment\Edit\Tab;

class General extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
	public $booleanOptions;

    /**
     * constructor
     *
     * @param \Magento\Config\Model\Config\Source\Yesno $booleanOptions
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Config\Model\Config\Source\Yesno $booleanOptions,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {

        $this->booleanOptions = $booleanOptions;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $attachment = $this->_coreRegistry->registry('talv_attachments_attachment');
        /** @var \Talv\ProductAttachments\Model\Attachment $attachment */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('attachment_');
        $form->setFieldNameSuffix('attachment');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('General Information'),
                'class'  => 'fieldset-wide'
            ]
        );

        if ($attachment->getId()) {
            $fieldset->addField(
                'attachment_id',
                'hidden',
                ['name' => 'attachment_id']
            );

		}

		$fieldset->addField(
            'name',
            'text',
            [
                'name'  => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
                'note' => __('Attachment name'),
            ]
        );

        $fieldset->addField(
            'enabled',
            'select',
            [
                'name'  => 'enabled',
                'label' => __('Enabled'),
                'title' => __('Enabled'),
                'values' => $this->booleanOptions->toOptionArray(),
            ]
        );

		$fieldset->addField(
            'file',
            'file',
            [
                'name'  => 'file',
                'label' => __('File'),
                'title' => __('File'),
                // 'required' => true,
                'note' => __('Attachment file'),
                'after_element_html' => $attachment->getFilename() ? $attachment->getFilename() . ' (' . $attachment->getFileSize() . ')' : '-',
            ]
        );

        $attachmentData = $this->_session->getData('talv_attachments_attachment_data', true);
        if ($attachmentData) {
            $attachment->addData($attachmentData);
        } else {
            if (!$attachment->getId()) {
                $attachment->addData($attachment->getDefaultValues());
            }
        }
        $form->addValues($attachment->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('General Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}
