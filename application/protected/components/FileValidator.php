<?php

class FileValidator extends CFileValidator {

    /**
     * Returns the JavaScript needed for performing client-side validation.
     * @param CModel $object the data object being validated
     * @param string $attribute the name of the attribute to be validated.
     * @return string the client-side validation script.
     * @see CActiveForm::enableClientValidation
     * @since 1.1.7
     */
    public function clientValidateAttribute($object, $attribute) 
    {
        $js = '';

        if (!$this->allowEmpty) 
        {

            $message = $this->message;
            if ($message == null)
                $message = Yii::t('yii', '{attribute} cannot be blank.');

            $message = strtr($message, array(
                '{attribute}' => $object->getAttributeLabel($attribute),
                    ));
            $js .= '
            if($.trim(value)==""){messages.push(' . CJSON::encode($message) . ');}
            ';
        }

        if ($this->types !== null) 
        {
            if (is_string($this->types))
                $types = preg_split('/[\s,]+/', strtolower($this->types), -1, PREG_SPLIT_NO_EMPTY);
            else
                $types = $this->types;

            $message = $this->wrongType;
            if ($message == null)
                $message = Yii::t('yii', 'The file "{file}" cannot be uploaded. Only files with these extensions are allowed: {extensions}.');
            $message = strtr($message, array(
                '{file}' => ':file',
                '{extensions}' => implode(', ', $types),
                    ));

            $js .= "
            if(['" . implode("','", $types) . "'].indexOf($.trim(value).split('.').pop().toLowerCase()) == -1".($this->allowEmpty?" && $.trim(value)!=''":'').")
            {
                messages.push('" . $message . "'.replace(':file', $.trim(value)));
            }
            ";
        }

        /**
         * Check the maxfile size setting
         */
        if ($this->maxSize !== null) 
        {
            $message = $this->tooLarge !== null ? $this->tooLarge : Yii::t('yii','The file "{file}" is too large. Its size cannot exceed {limit} bytes.');
            $message = strtr($message, array(
                '{file}' => ':file',
                '{limit}' => $this->bytesToSize($this->getSizeLimit()),
                ));
            
            $inputId = get_class($object)."_".$attribute;
            
            $js .= "
            if ($('#$inputId')[0].files[0]){
                var fileSize = $('#$inputId')[0].files[0].size; 
                if(fileSize>$this->maxSize){
                    messages.push('" . $message . "'.replace(':file', $.trim(value)));
                }
            }
            ";
        }
        return $js;
    }
    
    /**
     * Конвертерр байтового размера файла
     */
    private function bytesToSize($bytes) 
    {
        $sizes = array('байт', 'KB', 'MB', 'GB', 'TB');
        
        if ($bytes == 0) 
            return 'n/a';
        $i = intval(floor(log($bytes) / log(1024)));
        
        if ($i == 0) 
            return $bytes . ' ' . $sizes[$i];
            
        return round(($bytes / pow(1024, $i)),1,PHP_ROUND_HALF_UP). ' ' . $sizes[$i];
     }
}