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