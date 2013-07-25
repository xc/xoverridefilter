X Override Filter
=================

Introduction
-----------
With this simple extension, you can define your business logic(a php class) with template override in override.ini, so business logic can **easily and clearly** be done in php instead of custom operator, complicated templating, or datatype.

Small example:

Definition in override.ini

    [myform_view]
    Source=node/view/full.tpl
    MatchFile=form.tpl
    Subdir=templates
    Match[class_identifier]=myform
    Class=myFormView

Implementation in class myFormView

    class myFormView implements xNodeviewRender
    {
      function myFormView( $module, $node, $tpl, $viewMode )
      {
        //implement business logic, set template variable
      }
    }

Enhanced override.ini
---------------------
Enhanced override.ini supports

1. Class tag to identify a php class implenetation of the logic.
2. Match[node], Match[class_identifer], Match[viewmode] for view logic conditions

The 2 above can be combined with existing template override.


Install
--------
1. Copy this extension under <ezp_root>/extension
2. Activate this extension
3. Clear ini cache
4. Regenerate autoload array


Example(See doc/example folder for example code)
---------


1. Configure condition and class under myextension

   **Scenario 1 - Custom view logic** 
  
   extension/myextension/settings/override.ini.append.php.

        [myform_view]
        Match[class_identifier]=myform
        Class=myFormView
     
   The configuration above means that ‘myform’ objects will use myFormView for view logic. Form templates can be defined in additional template override rules.

   **Scenario 2 - Custom view logic with custom template**. You can also combine view logic with template override in one override rule. 

        [myform_view_2]
        Source=node/view/full.tpl
        MatchFile=form.tpl
        Subdir=templates
        Match[class_identifier]=myform
        #Condition section_identifier will be ignored by custom view logic.
        Match[section_identifier]=standard
        Class=myFormView

   The configuration above means that, 'myform' objects under Standard section will use class myFormView as view logic and form.tpl as template; while 'myform' objects under other sections will use myFormView as view logic and full.tpl(if no other override rule applies) as template.

2. Implement class myFormView

    extension/myextension/classes/myformview.php

        <?php
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
          public function initNodeview( $module, $node, $tpl, $viewMode, $http )
          {
            // Actual logic is implemented here

          }
        }
        ?>

3. Regenerated autoload array for extension
<php path> bin/php/ezpgenerateautoloads.php -e

4. Clear cache before viewing the page(content/view/full/50).
