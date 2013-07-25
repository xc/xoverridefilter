<?php
//
// Definition of myFormView class
//
// Created on: <25-07-2013 16:13:00 xc>
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
class myFormView implements xNodeviewRender
{
    /**
     * This method is invoked before template is fetched.
     *
     * Typical usage:
     * 1. Set php variable to template directly instead of use eZ custom operator in template
     * 2. Customize http form action
     *
     */
    public function initNodeview( $module, $node, $tpl, $viewMode )
    {
        // Disable view cache for this page, since the form will be dynamic
        $tpl->setVariable( 'cache_ttl', 0 );

        $http = eZHTTPTool::instance();
        if( $http->hasVariable( 'SubmitButton' ) )
        {
            // Show result if the form submit
            $name = $http->variable( 'name' );
            $email = $http->variable( 'email' );
            $tpl->setVariable( 'result', ezpI18n::tr( 'example', "You inputed Name: %1, Email %2", '', array( $name, $email )  ) );
        }
        else if( $http->hasVariable( 'DiscardButton' ) )
        {
            // Redirect to homepage if the form is discarded.
            $module->redirectTo( '/' );
        }

    }
}
?>