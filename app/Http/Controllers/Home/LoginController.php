<?php

namespace App\Http\Controllers\Home;

use App\User;
use App\UserLogin\OtherLogin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Overtrue\Socialite\SocialiteManager;

class LoginController extends Controller
{
    protected $login;

    public function __construct(OtherLogin $login)
    {
        $this->login = $login;
    }

    protected $config = [
        'weibo' => [
            'client_id' => '2630420162',
            'client_secret' => 'caa3a434be7ca19afe80197eea1d9db1',
            'redirect' => 'https://kobeman.com/weibo/login'
        ],
        'qq' => [
            'client_id' => '1105724901',
            'client_secret' => 'w7l38XW12b5ab88r',
            'redirect' => 'http://localhost:8000/qq/login'
        ],
        'github' => [
            'client_id' => '81ef36deca1d9eb5298b',
            'client_secret' => '8c429e4f6b3fa50b1c555d66a72206e5039e533e',
            'redirect' => 'http://localhost:8000/github/login'
        ],
        'weixin' => [
            'client_id' => '81ef36deca1d9eb5298b',
            'client_secret' => '8c429e4f6b3fa50b1c555d66a72206e5039e533e',
            'redirect' => 'http://localhost:8000/weixin/login'
        ],
    ];

    public function driver($style)
    {
        $socialite = new SocialiteManager($this->config);
        return $socialite->driver($style)->redirect();
    }

    public function githubLogin()
    {
//        $this->login->githubLogin($this->config);

        $socialite = new SocialiteManager($this->config);
        $githubUser = $socialite->driver('github')->user();//user就可以拿到igthub的公共信息

        //第一次用户登录
        $loginUser = User::where('social_type', 'github')->where('social_id', $githubUser->getId())->first();
        //如果没有查到这个用户 重定向到首页
        if (!is_null($loginUser)) {
            \Auth::loginUsingId($loginUser->id);
            return redirect('/');
        }
        $user = [
            'name' => $githubUser->getNickName(),
            'email' => $githubUser->getEmail(),
            'password' => bcrypt(str_random(16)),
            'social_type' => 'github',
            'social_id' => $githubUser->getId()
        ];
        $data = [
            'is_confirmed' => 1,
            'confirm_code' => str_random(48),
            'avatar' => $githubUser->getAvatar(),
        ];
        $newUser = User::create(array_merge($user, $data));
        \Auth::loginUsingId($newUser->id);
        return redirect('/');
    }

    public function weiboLogin()
    {
        $socialite = new SocialiteManager($this->config);
        $githubUser = $socialite->driver('weibo')->user();//user就可以拿到igthub的公共信息

        //第一次用户登录
        $loginUser = User::where('social_type', 'weibo')->where('social_id', $githubUser->getId())->first();
        //如果没有查到这个用户 重定向到首页
        if (!is_null($loginUser)) {
            \Auth::loginUsingId($loginUser->id);
            return redirect('/');
        }
        $user = [
            'name' => $githubUser->getNickName(),
            'email' =>'example@ispace.com',
            'password' => bcrypt(str_random(16)),
            'social_type' => 'weibo',
            'social_id' => $githubUser->getId()
        ];
        $data = [
            'is_confirmed' => 1,
            'confirm_code' => str_random(48),
            'avatar' => $githubUser->getAvatar(),
        ];
        $newUser = User::create(array_merge($user, $data));
        \Auth::loginUsingId($newUser->id);
        return redirect('/');
    }

    public function qqLogin()
    {

    }
}
