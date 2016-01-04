<!-- Language selection -->
<ul class="nav">
    <li class="<?php if ($lang==='en'): ?>active<?php endif; ?>">
        <a href="<?php echo EngineBlock_View::htmlSpecialCharsText(EngineBlock_View::setLanguage('en')); ?>">EN</a>
    </li>
    <li class="<?php if ($lang==='nl'): ?>active<?php endif; ?>">
        <a href="<?php echo EngineBlock_View::htmlSpecialCharsText(EngineBlock_View::setLanguage('nl')); ?>">NL</a>
    </li>
</ul>
