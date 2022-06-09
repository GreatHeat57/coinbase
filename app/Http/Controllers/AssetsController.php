<?php
         
namespace App\Http\Controllers;
          
use App\Models\Asset;
use Illuminate\Http\Request;
use DataTables;
        
class AssetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
   
        $assets = Asset::latest()->get();

        if ($request->ajax()) {            
            return Datatables::of($assets)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editAsset">Edit</a>';
   
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAsset">Delete</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('assets', compact('assets'));
    }
     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Asset::updateOrCreate(['id' => $request->product_id],
                ['name' => $request->name, 'price_usd' => $request->price, 'asset_id' => $request->assetId]);
   
        return response()->json(['success'=>'Asset saved successfully.']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $asset = Asset::find($id);
        return response()->json($asset);
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Asset::find($id)->delete();
     
        return response()->json(['success'=>'Asset deleted successfully.']);
    }
}