<?php

use Illuminate\Support\Facades\Auth;

function lang($key)
{
    return trans('lang.'.$key);
}

function mapColorSizes($pid)
{
    $map = array();
    if(productHasColor($pid)) {
        $colorIDs = Image::where('product_id',$pid)->get()->pluck('color_id')->toArray();
        $colorIDs = array_unique($colorIDs);
        foreach($colorIDs as $id) {
            $color = Color::find($id);
            $sizeIDs = Image::where(['product_id'=>$pid,'color_id'=>$id])->get()->pluck('size_id')->toArray();
            $color['sizes'] = Size::whereIn('id',$sizeIDs)->get();
            $map[] = $color;
        }
    }
    return $map;
}

function language()
{
    $code = App::getLocale();
    return Language::where('code','!=',$code)->first();
}

function langCode()
{
    return App::getLocale();
}
