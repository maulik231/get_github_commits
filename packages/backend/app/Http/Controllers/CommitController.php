<?php

namespace App\Http\Controllers;

use App\Models\Commit;
use App\Models\RepoUrl;
use Illuminate\Http\Request;

class CommitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $hit = "https://api.github.com/repos/dgoguerra/git-public-url/commits";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $hit,
            CURLOPT_USERAGENT => 'http://YOUR-DOMAIN'
        ));
        $resp = curl_exec($curl);
        curl_close($curl);

        $commit_data = json_decode($resp, true);
        // echo "<pre>";
        //   print_r($commit_data);
        // exit();
        if(is_array($commit_data)){echo count($commit_data);
            foreach ($commit_data as $data =>$res ){
                $commit_id = $res['sha'];
                $repo_id = 18;
                $committer_name = $res['commit']['committer']['name'];
                $committer_email = $res['commit']['committer']['email'];
                $committer_date = $res['commit']['committer']['date'];
                $commit_message = $res['commit']['message']; 
                
            $data=new Commit;
            $data->commit_id=$commit_id;
            $data->repo_id=18;
            $data->committer_name=$committer_name;
            $data->committer_email=$committer_email;
            $data->committer_date=$committer_date;
            $data->commit_message=$commit_message;
            $data->save();
               if($data){
                 response(['Commit' => $data, 'status' => 200, 'message' => 'commit data']);
               }
               else{
                return response([ 'status' => 422, 'message' => 'commit data not inserted']);
               }
            }
        }
        else{
            return response([ 'status' => 422, 'message' => 'Please enter valid url']);
        }
    }

    public function getData(){
        $repoUrl_data =  RepoUrl::orderBy('id','DESC')->get();
        if(count($repoUrl_data)>0){
            return response(['repo_url' => $repoUrl_data, 'status' => 200, 'message' => 'repo data retrieved successfully']);
        }
        else{
            return response(['errors' => "not found repos data", 'status' => 422]);
        }
    }

    public function getcommitdata(Request $r){
        $id= $r->repo_id;
        if($id){
            $commit_data =  Commit::where('repo_id',$id)->orderBy('committer_date','DESC')->paginate(15);
            if(count($commit_data)>0){
                return response(['commit_data' => $commit_data, 'status' => 200, 'message' => 'repo data retrieved successfully'],200);
            }
            else{
                return response(['errors' => "not found any commit data", 'status' => 422]);
            }
        }
        else{
            return response(['errors' => "not found any commit data", 'status' => 422]);
        }
    }

    public function repoUrl(Request $request){
        $validated = $request->validate([
            'repo_url' => 'required|unique:repo_urls|max:255',
        ]);
        $arrays=explode("/",$request->repo_url);
        if(count($arrays)==5 && $arrays[2]=="github.com"){
            $arrays[2]="api.github.com";
            $username=$arrays[3];
            $reponame=$arrays[4];
            $arrays[3]="repos";
            $arrays[4]=$username;
            $arrays[5]=$reponame;
            $arrays[6]="commits";

            $newurl=implode("/",$arrays);
            $repoUrl_data =  RepoUrl::orderBy('id','DESC')->get();
            if(count($repoUrl_data)>0){
                foreach($repoUrl_data as $key =>$value){
                    if($value['repo_url']==$newurl){
                        return response(['errors'=> 'Url already exists' , 'status' => 422],422);
                    }
                }
            }
            $data=array("repo_url"=>$newurl);
            $repodata=RepoUrl::create($data);
            if($repodata){
                $id=$repodata->id;
                $hit = $newurl;
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $hit,
                    CURLOPT_USERAGENT => 'http://YOUR-DOMAIN'
                ));
                $resp = curl_exec($curl);
                curl_close($curl);
                $commit_data = json_decode($resp, true);
                if(is_array($commit_data)){
                    foreach ($commit_data as $data =>$res ){
                        if(isset($res['sha'])){
                            $commit_id = $res['sha'];
                            $committer_name = $res['commit']['committer']['name'];
                            $committer_email = $res['commit']['committer']['email'];
                            $committer_date = $res['commit']['committer']['date'];
                            $commit_message = $res['commit']['message'];
                            $data=new Commit;
                            $data->commit_id=$commit_id;
                            $data->repo_id=$id;
                            $data->committer_name=$committer_name;
                            $data->committer_email=$committer_email;
                            $data->committer_date=$committer_date;
                            $data->commit_message=$commit_message;
                            $data->save();
                            if($data){
                                response(['Commit' => $data, 'status' => 200, 'message' => 'commit data'],200);
                            }
                            else{
                                return response(['errors'=> 'commit data not inserted', 'status' => 422],422);
                            }
                        }
                        else{
                            RepoUrl::where('id', $repodata->id)->delete();
                            return response(['errors'=> 'Please enter a valid URL ', 'status' => 422],422);
                        }
                    }
                }
                else{
                    return response(['errors'=> 'commit data not inserted', 'status' => 422],422);
                }
                return response(['repoURL' => $repodata, 'status' => 200, 'message' => 'Get Repo URL']);
            }
            else{
                return response(['errors'=> 'repourl not inserted', 'status' => 422],422);
            }
        }
        else{
            return response(['errors'=> 'Please enter a valid URL ', 'status' => 422],422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Commit  $commit
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $commit_data =  Commit::orderBy('id','DESC')->get();
        return response(['commit_data' => $commit_data, 'status' => 200, 'message' => 'commit data retrieved successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Commit  $commit
     * @return \Illuminate\Http\Response
     */
    public function edit(Commit $commit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Commit  $commit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Commit $commit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Commit  $commit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Commit $commit)
    {
        //
    }
}
