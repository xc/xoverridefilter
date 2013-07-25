<?php
//
// Definition of xNodeviewRender class
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
// Contact licence@grenlandweb.no if any conditions of this licencing isn't clear to
// you.
//

 
interface xNodeviewRender
{
    public function initNodeview( $node, $tpl, $viewMode, $http );
}
