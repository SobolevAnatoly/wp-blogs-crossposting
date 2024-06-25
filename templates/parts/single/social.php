<div class="blog__social">
    <a href="http://facebook.com/sharer.php?u=<?php
    the_oro_post_shortlink(get_permalink()); ?>&amp;t=<?php
    echo urlencode(get_the_title()) ?>"
       class="blog__social-link facebook social-share-facebook"
       target="_blank" title="Share on Facebook">
    </a>
    <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php
    the_oro_post_shortlink(get_permalink()); ?>&amp;text=<?php
    echo urlencode(get_the_title()) ?>"
       class="blog__social-link linkedin social-share-twitter"
       target="_blank" title="Share on Twitter">
    </a>
    <a href="http://twitter.com/intent/tweet?url=<?php
    the_oro_post_shortlink(get_permalink()); ?>&amp;title=<?php
    echo urlencode(get_the_title()) ?>"
       class="blog__social-link twitter social-share-linkedin"
       target="_blank" title="Share on LinkedIn">
    </a>
</div>