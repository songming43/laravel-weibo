<?php

namespace App\Http\Controllers;

use Auth;


use Illuminate\Http\Request;


class SessionController extends Controller
{
    /**
     * 用户登录页面展示
     */
    public function create(){

        return view('sessions.create');
    }

    /**
     * 创建会话信息(用户登录)
     */
    public function store(Request $request){
        $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];


        if(Auth::attempt($credentials,$request->has('remember'))){
            //如果用户账号已经被激活
            if(Auth::user()->activated){
                session()->flash('success','欢迎回来!');
                return redirect()->intended(route('users.show',[Auth::user()]));
            }else{
                Auth::logout(); //用户退出登录(清除session)
                session()->flash('warning','您的账号未激活,请检查邮箱中的注册邮件进行激活'); //把一条数据暂存在session中
                return redirect();
            }

        }else{
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配!');
            return redirect()->back();
        }
    }

    /**
     * 删除会话信息(用户退出)
     */
    public function destroy(){
        Auth::logout();
        session()->flash('success','您已成功退出!');
        return redirect()->route('login');
    }
}
