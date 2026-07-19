<article class="campaign-card glass lift">
  <a class="campaign-cover category-<?=e(strtolower($campaign['category']))?>" href="<?=url('campaign.php?slug='.urlencode($campaign['slug']))?>">
    <?php if(!empty($campaign['cover_image'])): ?><img src="<?=url($campaign['cover_image'])?>" alt="<?=e($campaign['title'])?>"><?php else: ?><span class="cover-icon"><?=category_icon($campaign['category'])?></span><?php endif; ?>
    <span class="campaign-category"><?=e($campaign['category'])?></span>
  </a>
  <div class="campaign-body">
    <h3><a href="<?=url('campaign.php?slug='.urlencode($campaign['slug']))?>"><?=e($campaign['title'])?></a></h3>
    <p><?=e($campaign['summary'])?></p>
    <div class="progress"><span style="width:<?=campaign_progress($campaign)?>%"></span></div>
    <div class="campaign-meta"><strong><?=money((float)$campaign['raised'])?></strong><span>dari <?=money((float)$campaign['target_amount'])?></span></div>
    <div class="campaign-foot"><span><?=number_format((int)$campaign['donor_count'])?> donatur</span><span><?=e($campaign['location'])?></span></div>
  </div>
</article>
