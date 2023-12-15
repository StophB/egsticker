<div class="panel">
    <div class="row h-100">
        <div class="col-md-7 my-auto">

            <form method="post" action="">

                <div class="mt-2 row">
                    <div class="col-md-6">
                        <label class="form-control-label">
                            {l s='Select a sticker:' mod='egsticker'}
                        </label>
                        {foreach from=$stickers item=sticker}
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sticker_img" id="sticker_{$file.img|escape:'htmlall':'UTF-8'}" value="{$file.img|escape:'htmlall':'UTF-8'}">
                                <label class="form-check-label" for="sticker_{$sticker.image|escape:'htmlall':'UTF-8'}">
                                    <img src="{$imgPath}{$sticker.image|escape:'htmlall':'UTF-8'}" alt="Sticker Image" style="max-width: 50px; max-height: 50px;">
                                </label>
                            </div>
                        {/foreach}
                    </div>
                </div>

                <div class="mt-2 row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">
                            {l s='Add' mod='egsticker'}
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
