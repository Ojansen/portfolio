<?php

namespace App\Http\Controllers;

// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\Project;
use App\Post;

class PortfolioController extends Controller
{

    public function Project()
    {
        $projecten = Project::all();
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'https://api.github.com/users/Ojansen/repos');

        $repos = json_decode($res->getBody());

        foreach ($repos as &$repo) {
            $repo_download = $client->request('GET', 'https://api.github.com/repos/Ojansen/'.$repo->name.'/commits');
            $repo->commits = json_decode($repo_download->getBody());
        }

        return view('projects', ['projecten' => $projecten, 'repos' => $repos]);
    }

    public function Blog()
    {
        $posts = Post::all();
        return view('blog', ['posts' => $posts]);
    }

    public function Like(Request $request)
    {
        $post = Post::find($request->input('post'));
        $post->likes = $post->likes + 1;
        $post->save();
        return redirect()->route('blog');
    }
}
