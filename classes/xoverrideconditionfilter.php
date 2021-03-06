<?php
//
// Definition of xOverrideConditionFilter class
//
// Created on: <25-07-2013 12:05:00 xc>
//
// Copyright (C) Aplia AS. All rights reserved.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
//

 
class xOverrideConditionFilter
{
    public static function filter( $module, $node, $tpl, $viewMode )
    {
        //Make it compatable with patches before 5.2 and 5.2(no patch) because a patch reverse
        //Ref.https://github.com/ezsystems/ezpublish-legacy/commit/85ab7fb8374f31c5cba00450e71d27e456552878
        if( !$module instanceof eZModule )
        {
            $viewMode = $tpl;
            $tpl = $node;
            $node = $module;
            $module = null;
        }
        //End of compatability fix

        $ini = eZINI::instance( 'override.ini' );
        $conditions = $ini->groups();

        $nodeID = $node->attribute( 'node_id' );
        $object = $node->attribute( 'object' );

        $classIdentifier = $object->attribute( 'class_identifier' );
        $overrideClass = null;

        foreach( $conditions as $condition )
        {
            if( isset( $condition['Match'] ) )
            {
                $matches = $condition['Match'];

                // node condition
                $matchNode = null;
                if( isset( $matches['node'] ) )
                {
                    if( $matches['node'] == $nodeID )
                    {
                        $matchNode = true;
                    }
                    else
                    {
                        $matchNode = false;
                    }
                }

                // class_identifier condition
                $matchClass = null;
                if( isset( $matches['class_identifier'] )  )
                {
                    if( $matches['class_identifier'] == $classIdentifier )
                    {
                        $matchClass = true;
                    }
                    else
                    {
                        $matchClass = false;
                    }

                }

                // view mode condition
                $matchViewmode = null;
                if( isset( $matches['viewmode'] ) )
                {
                    if( $matches['viewmode'] == $viewMode )
                    {
                        $matchViewmode = true;
                    }
                    else
                    {
                        $matchViewmode = false;
                    }
                }

                $useIt = false;
                // When viewmode is not set or viewmode matches
                if( !isset( $matchViewmode ) || $matchViewmode === true )
                {
                    // When class_identifier is not set or class_identifier matches
                    if( !isset( $matchClass ) || $matchClass === true )
                    {
                        // When node(id) is not set or node(id) matches
                        if( !isset( $matchNode ) || $matchNode === true )
                        {
                            if( isset( $condition['Class'] ) )
                            {
                                $overrideClass = $condition['Class'];
                                break;
                            }
                        }
                    }
                }
            }
        }

        //Support Match[attribute_<attribute_identifier>]=<value> in override.ini
        $object = $node->attribute( 'object' );
        $dataMap = $object->dataMap();

        $ini = eZINI::instance( 'xoverride.ini' );
        $siteAccessesMatch = false;
        if( !$ini->hasVariable( 'TemplateOverride', 'AvailableSiteAccess' ) )
        {
            $siteAccessesMatch = true;
        }
        else
        {
            $siteAccesses = $ini->variable( 'TemplateOverride', 'AvailableSiteAccess' );
            $currentSiteAccessArray = eZSiteAccess::current();
            $currentSiteAccess = $currentSiteAccessArray['name'];
            if( is_array( $siteAccesses ) && ( in_array( $currentSiteAccess, $siteAccesses ) or in_array( '*', $siteAccesses ) ) )
            {
                $siteAccessesMatch = true;
            }
        }
        if( $siteAccessesMatch )
        {
            $supportedDatatype = $ini->variable( 'General', 'SupportedDatatype' );
            $keys = array();
            foreach( $dataMap as $attributeId=>$attribute )
            {
                $dataType = $attribute->attribute( 'data_type_string' );
                if( in_array( $dataType, $supportedDatatype ) )
                {
                    $value = $attribute->attribute( 'content' );
                    $keys[] = array( 'attribute_' . $attributeId, $value );
                }
            }
            $res = eZTemplateDesignResource::instance();
            $res->setKeys( $keys );

            if( !empty( $overrideClass ) )
            {
                $overrideView = new $overrideClass();
                $http = eZHTTPTool::instance();
                eZDebug::writeNotice( "Loading nodeview render $overrideClass, node id: $nodeID", __METHOD__ );
                $overrideView->initNodeview( $module, $node, $tpl, $viewMode );
            }
        }
    }
}
