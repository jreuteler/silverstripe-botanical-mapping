<?php


class BotanicalMappingHelper
{

    public static function isValidDataObjectName($dataObjectName, $checkForFrontendAccess = false)
    {

        if (isset($dataObjectName) && class_exists($dataObjectName) ) {

            try {

                $dataObject = $dataObjectName::create();
                if ($checkForFrontendAccess) {

                    if ($dataObject::$allow_frontend_access === true)
                        return true;
                    else
                        return false;

                }

                return true;

            } catch (Exception $e) {
            }

        }

        return false;

    }
}