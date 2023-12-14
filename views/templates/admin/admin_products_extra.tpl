<div class="panel">
    <div class="row h-100">
        <div class="col-md-7 my-auto">

            <form method="post" action="">

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-control-label">
                            {l s='Name:' mod='egsticker'}
                        </label>
                        <input type="text" name="sticker_name" class="form-control" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-control-label">
                            {l s='Select a sticker:' mod='egsticker'}
                        </label>
                        {foreach from=$stickerslist item=file}
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sticker" id="sticker_{$file.img|escape:'htmlall':'UTF-8'}" value="{$file.img|escape:'htmlall':'UTF-8'}">
                                <label class="form-check-label" for="sticker_{$file.img|escape:'htmlall':'UTF-8'}">
                                    <img src="{$imgPath}{$file.img|escape:'htmlall':'UTF-8'}" alt="Sticker Image" style="max-width: 50px; max-height: 50px;">
                                </label>
                            </div>
                        {/foreach}
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sticker" id="no_sticker" value="">
                            <label class="form-check-label" for="no_sticker">
                                {l s='No Sticker' mod='egsticker'}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-control-label">
                            {l s='Select position:' mod='egsticker'}
                        </label>
                        <select name="position" class="form-control">
                            <option value="0">{l s='Top right' mod='egsticker'}</option>
                            <option value="1" selected>{l s='Top left' mod='egsticker'}</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">
                            {l s='Save' mod='egsticker'}
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
