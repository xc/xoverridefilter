<?php
//
// Definition of overrideCondition class
//
// Created on: <25-07-2013 12:05:00 xc>
//
// Copyright (C) Chen Xiongjie. All rights reserved.
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
    public static function filter( $node, $tpl, $viewMode, $http )
    {
        $ini = eZINI::instance( 'override.ini' );
        $conditions = $ini->groups();

        $nodeID = $node->attribute( 'node_id' );
        $object = $node->attribute( 'object' );
        $classIdentifier = $object->attribute( 'class_identfier' );

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

        if( !empty( $overrideClass ) )
        {
            $overrideView = new $overrideClass();
            $http = eZHTTPTool::instance();
            eZDebug::writeNotice( "Loading view logic $overrideClass", __METHOD__ );
            $overrideView->initNodeview( $node, $tpl, $viewMode, $http );
        }
    }
}
