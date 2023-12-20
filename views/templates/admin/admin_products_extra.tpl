<div class="panel">
    <div class="row h-100">
        <div class="col-md-7 my-auto">
                <div class="mt-2 row">
                    <div class="col-md-6">
                        <label class="form-control-label">
                            {l s='Select a sticker:' mod='egsticker'}
                        </label>
                        {foreach from=$stickers item=sticker}
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sticker" id="sticker_{$sticker.id_eg_sticker|escape:'htmlall':'UTF-8'}" value="{$sticker.id_eg_sticker|escape:'htmlall':'UTF-8'}">
                                <label class="form-check-label" for="sticker_{$sticker.id|escape:'htmlall':'UTF-8'}">
                                    <img src="{$imgPath}{$sticker.image|escape:'htmlall':'UTF-8'}" alt="{$sticker.name|escape:'htmlall':'UTF-8'}" style="max-width: 50px; max-height: 50px;">
                                </label>
                            </div>
                        {/foreach}
                    </div>
                </div>

                <div class="mt-2 row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-default pull-right">
                            <i class="process-icon-save"></i>{l s='Ajouter un sticker' mod='egsticker'}
                        </button>
                    </div>
                </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <h3 class="card-header">
                    {l s='Liste de stickers ajouter' mod='egsticker'}
                </h3>
                <div class="card-block">
                    <table class="table eg_special_feature" id="eg-list-special-feature">
                        <thead>
                        <tr class="nodrag nodrop">
                            <th class=""></th>
                            <th class="">
                                <span class="title_box active">{{ 'ID'|trans({}, 'Modules.Egproductinfos.Admin') }}</span>
                            </th>
                            <th class="">
                                <span class="title_box">{{ 'Image'|trans({}, 'Modules.Egproductinfos.Admin') }}</span>
                            </th>
                            <th class="">
                                <span class="title_box">{{ 'Titre'|trans({}, 'Modules.Egproductinfos.Admin') }}</span>
                            </th>
                            <th class="">
                                <span class="title_box">{{ 'Statut'|trans({}, 'Modules.Egproductinfos.Admin') }}</span>
                            </th>
                            <th class="">
                                <span class="title_box">{{ 'Action'|trans({}, 'Modules.Egproductinfos.Admin') }}</span>
                            </th>
                            <th class="">
                                <span class="title_box"></span>
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="eg_feature_position">
                        {% for feature in egSpecialFeature %}
                        <tr id="{{ feature.id_eg_product_feature }}" class="odd">
                            <td class="pointer" onclick="">
                                <div class="position-drag-eg-special-feature">
                                    <i class="material-icons">drag_indicator</i>
                                </div>
                            </td>
                            <td class="pointer" onclick=""> {{ feature.id_eg_product_feature }}</td>
                            <td class="pointer" onclick="">
                                <img
                                        src="{{ uri }}{{ feature.feature_img }}"
                                        width="40"
                                        height="40"
                                        alt="info"
                                        class="img img-thumbnail">
                            </td>
                            <td class="pointer" onclick=""> {{ feature.feature_title }} </td>
                            <td class="pointer" onclick="">
                                <div id="">
                                    {% if feature.active|default(0) == 0 %}
                                    <a href="#" onclick="updateStatusEgSpecialFeature({{feature.id_eg_product_feature}}, 1); return false;">
                                        <i class="material-icons eg-action-disabled">clear</i>
                                    </a>
                                    {% else %}
                                    <a href="#" onclick="updateStatusEgSpecialFeature({{feature.id_eg_product_feature}}, 0); return false;">
                                        <i class="material-icons eg-action-enabled">check</i>
                                    </a>
                                    {% endif %}
                                </div>
                            </td>
                            <td class="pointer" onclick="">
                                <div class="btn-group-action">
                                    <div class="btn-group">
                                        <a
                                                class="pop_up_piece attr_info btn btn-default"
                                                href="{{EgProductSpecialFeatureController}}&liteDisplaying=1&updateeg_product_feature&realedit=1&id_eg_product_feature={{feature.id_eg_product_feature}}">
                                            <i class="material-icons">mode_edit</i>
                                        </a>
                                        <a href="#" onclick="deleteEgSpecialFeature({{feature.id_eg_product_feature}}); return false;" class="btn tooltip-link product-edit"
                                           data-toggle="pstooltip" id="" title="" data-placement="right" data-original-title="Supprimer définitivement cette info.">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
