{if is_set( $result )}
<div>{$result}</div>
{/if}
<form action="" method="post">
    <div>
        {"Name:"|i8n('example')} <input type="text" name="name" value="" />
    </div>
    <div>
        {"Email:"|i18n('example')} <input type="text" name="email" value="" />
    </div>
    <div>
        <input type="submit" name ="SubmitButton" value={'Submit'|i18n( 'example' )} />
        <input type="submit" name ="DiscardButton" value={'Discard'|i18n( 'example' )} />
    </div>
</form>