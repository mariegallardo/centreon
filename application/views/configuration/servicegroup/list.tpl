{extends file="../../viewLayout.tpl"}

{block name="title"}Service Group{/block}

{block name="content"}
    {datatable object='Servicegroup' objectAddUrl='/configuration/servicegroup/add'}
{/block}

{block name="javascript-bottom" append}
    {datatablejs object='Servicegroup' objectUrl='/configuration/servicegroup/list'}
{/block}