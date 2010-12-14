/**
 * loginName
 *
 * Conditional name for the Login menu item
 *
 * @category        tv
 * @name            loginName
 * @internal        @caption loginName
 * @internal        @input_type text
 * @internal        @input_options
 * @internal        @input_default @EVAL if ($modx->getLoginUserID()) return 'Logout'; else return 'Login';
 * @internal        @output_widget
 * @internal        @output_widget_params
 * @internal        @lock_tv 0
 * @internal        @template_assignments MODxHost
 * @internal        @modx_category Demo Content
 * @internal        @installset sample
 */