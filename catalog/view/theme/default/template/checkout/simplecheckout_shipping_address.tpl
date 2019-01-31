<link rel='stylesheet' href='https://apimgmtstorelinmtekiynqw.blob.core.windows.net/content/MediaLibrary/Widget/Calc/styles/calc.css' />

<div class="simplecheckout-block" id="simplecheckout_shipping_address" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $display_error && $has_error ? 'data-error="true"' : '' ?>>
  <?php if ($display_header) { ?>
    <div class="checkout-heading"><?php echo $text_checkout_shipping_address ?></div>
  <?php } ?>
  <div class="simplecheckout-block-content">
    <?php foreach ($rows as $row) { ?>
      <?php echo $row ?>
    <?php } ?>
    <?php foreach ($hidden_rows as $row) { ?>
      <?php echo $row ?>
    <?php } ?>
  </div>
</div>

<div id="np-calc-body" class="np-w-br-5" style="width: 241px;"> <div class="np-calc-wrapper"> <div class="np-calc-logotype"></div> <div class="np-hl"></div> <span id="np-calc-title">Розрахунок вартості<br>доставки</span> <div class="np-calc-list"> <div class="np-calc-field" name="dispatch" role="CitySender"> <input type="text" class="np-option-search-item" placeholder="Звідки"> <div class="np-toggle-options-list"></div> <ul class="np-options-enter-point" role="CitySender"></ul> </div> <div id="np-arrows" name=""></div> <div class="np-calc-field" name="catch" role="CityRecipient"> <input type="text" class="np-option-search-item" placeholder="Куди"> <div class="np-toggle-options-list"></div> <ul class="np-options-enter-point" role="CityRecipient"></ul> </div> <div class="np-calc-field" name="weight" role="Weight"> <input type="text" class="np-option-search-item-weight" placeholder="Вага посилки"> </div> </div> <div class="np-line-background"></div> <button id="np-calc-submit" type="button"> <span id="np-text-button">Розрахувати</span> <div id="np-load-image"></div> </button> </div> <div id="np-cost-field"> <div class="np-cost-field-container"> <p id="np-cost-number"></p> <span>грн</span> </div> <div class="np-cost-info-container"> <span>Вартість доставки</span><br> <div id="np-current-city"></div> <span>вагою </span> <span id="np-current-weight"></span> <span>кг</span> </div> <div class="np-mini-logo"> <div class="np-line-left"></div> <div class="np-line-right"></div> </div> <a href="https://novaposhta.ua/delivery?utm_source=calc&amp;utm_medium=widget&amp;utm_term=calc&amp;utm_content=widget&amp;utm_campaign=NP" target="_blank"> Детальний розрахунок </a> <button type="button" id="np-cost-return-button">Інша посилка</button> </div> <div id="np-error-field"> <div class="np-status-logo"> <img src="https://apimgmtstorelinmtekiynqw.blob.core.windows.net/content/MediaLibrary/Widget/img/not-found.svg" alt="error icon"> </div> <div class="np-error-info-container"> <span>Вибачте! З технічних причин ми не змогли розрахувати Вартість посилки</span> </div> <div class="np-mini-logo"> <div class="np-line-left"></div> <div class="np-line-right"></div> </div> <button type="button" id="np-error-return-button">Інша посилка</button> </div> </div>

<script type='text/javascript' src='https://apimgmtstorelinmtekiynqw.blob.core.windows.net/content/MediaLibrary/Widget/Calc/dist/calc.min.js'></script>
