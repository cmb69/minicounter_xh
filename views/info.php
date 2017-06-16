<h1>Minicounter</h1>
<img src="<?=$this->logo()?>" alt="Plugin Icon" style="float: left; margin: 0 1.5em 0.5em 0; width: 128px; height: 128px">
<p>
    <strong><?=$this->text('text_admin', $this->count)?></strong>
</p>
<p>
    Version: <?=$this->version()?>
</p>
<p>
    Copyright © 2012-2017 <a href="http://3-magi.net/">Christoph M. Becker</a>
</p>
<p style="text-align: justify">
    Minicounter_XH is free software: you can redistribute it and/or modify it
    under the terms of the GNU General Public License as published by the Free
    Software Foundation, either version 3 of the License, or (at your option)
    any later version.
</p>
<p style="text-align: justify">
    Minicounter_XH is distributed in the hope that it will be useful, but
    <em>without any warranty</em>; without even the implied warranty of
    <em>merchantability</em> or <em>fitness for a particular purpose</em>. See
    the GNU General Public License for more details.
</p>
<p style="text-align: justify">
    You should have received a copy of the GNU General Public License along with
    Minicounter_XH. If not, see <a
    href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.
</p>
<div class="minicounter_syscheck">
    <h2><?=$this->text('syscheck_title')?></h2>
<?php foreach ($this->checks as $check):?>
    <p class="xh_<?=$this->escape($check->state)?>"><?=$this->text('syscheck_message', $check->label, $check->stateLabel)?></p>
<?php endforeach?>
</div>
