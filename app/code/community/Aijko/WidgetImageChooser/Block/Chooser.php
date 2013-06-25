<?php
/**
 * Image chooser element for widgets.
 *
 * @category    Aijko
 * @package     Aijko_WidgetImageChooser
 * @author      Gerrit Pechmann <gp@aijko.de>
 * @copyright   Copyright (c) 2012 aijko GmbH (http://www.aijko.de)
 */
class Aijko_WidgetImageChooser_Block_Chooser extends Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset_Element
{

    /**
     * Render element.
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $previewHtml = '';
        if ($element->getValue()) {

            // Add image preview.
            $url = $element->getValue();

            if( !preg_match("/^http\:\/\/|https\:\/\//", $url) ) {
                $url = Mage::getBaseUrl('media') . $url;
            }

            $previewHtml = '<a href="' . $url . '"'
                         . ' onclick="imagePreview(\'' . $element->getHtmlId() . '_image\'); return false;">'
                         . '<img src="' . $url . '" id="' . $element->getHtmlId() . '_image" title="' . $element->getValue() . '"'
                         . ' alt="' . $element->getValue() . '" height="40" class="small-image-preview v-middle"'
                         . ' style="margin-top:7px; border:1px solid grey" />'
                         . '</a> ';
        }

        $prefix = $element->getForm()->getHtmlIdPrefix();
        $elementId = $prefix . $element->getId();

        $chooserUrl = $this->getUrl('*/cms_wysiwyg_images_chooser/index', array('target_element_id' => $elementId));

        $label = ($element->getValue()) ? $this->__('Change Image') : $this->__('Insert Image');

        $chooseButton = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('btn-chooser')
            ->setLabel($label)
            ->setOnclick('MediabrowserUtility.openDialog(\'' . $chooserUrl . '\')')
            ->setDisabled($element->getReadonly())
            ->setStyle('display:inline;margin-top:7px');

        // Add delete button.
        $removeButton = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('delete')
            ->setLabel($this->__('Remove Image'))
            ->setOnclick('document.getElementById(\''. $elementId .'\').value=\'\';if(document.getElementById(\''. $elementId .'_image\'))document.getElementById(\''. $elementId .'_image\').parentNode.remove()')
            ->setDisabled($element->getReadonly())
            ->setStyle('margin-left:10px;margin-top:7px');

        $element->setData('after_element_html', $previewHtml . $chooseButton->toHtml() . $removeButton->toHtml());

        $this->_element = $element;
        return $this->toHtml();
    }
}