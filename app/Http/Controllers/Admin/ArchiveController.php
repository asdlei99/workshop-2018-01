<?php

namespace App\Http\Controllers\Admin;

use App\Archive;
use App\Http\Requests\StoreArchiveRequest;
use App\Http\ReturnHelper;
use App\Transformers\ArchiveTransformer;
use Cyvelnet\Laravel5Fractal\Facades\Fractal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArchiveController extends Controller
{

    public function create(Request $request)
    {
        $archive = new Archive();

        $parent_name = $request->input('parent');
        if($parent_name === null){
            $archive->parent_id = 0;
        }else{
            $parent = Archive::findByName($parent_name);
            if($parent === null) {
                return ReturnHelper::returnWithStatus('父类别不存在', 6011);
            }else{
                $archive->parent_id = $parent->id;
            }
        }

        $archive->name = $request->input('name');

        try{
            $archive->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('添加类别失败',6012);
        }

        return ReturnHelper::returnWithStatus(Fractal::item($archive, new ArchiveTransformer()));
    }

    public function update(Request $request, $id)
    {
        $archive = Archive::find($id);
        if($archive === null){
            return ReturnHelper::returnWithStatus('未找到指定类别',6013);
        }

        $parent_name = $request->input('parent');
        if($parent_name === null){
            $archive->parent_id = 0;
        }else{
            $parent = Archive::findByName($parent_name);
            if($parent === null) {
                return ReturnHelper::returnWithStatus('父类别不存在', 6011);
            }else{
                $archive->parent_id = $parent->id;
            }
        }

        $archive->name = $request->input('name');

        try{
            $archive->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('保存类别失败',6012);
        }

        return ReturnHelper::returnWithStatus(Fractal::item($archive, new ArchiveTransformer()));

    }

    public function destroy($id)
    {
        $archive = Archive::find($id);
        if($archive === null){
            return ReturnHelper::returnWithStatus('未找到指定类别',6013);
        }

        try{
            $archive->delete();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('删除类别失败',6014);
        }

        return ReturnHelper::returnWithStatus();
    }

}
