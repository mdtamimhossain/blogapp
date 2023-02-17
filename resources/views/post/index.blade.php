@extends('layouts.app')

@section('title')
    Post List
@endsection
@section('style')
    <style>
        .create_form_Card{
            position: absolute;
            top:50%;
            left:50%;
            width: 50vw;
            transform: translate(-50%,-50%);
        }
    </style>

@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
           post List
            <span class="btn btn-info" id="create_btn">Create Post</span>
        </div>
        <div class="card-body">
            {{--}}<ul class="list-unstyled">
                @if (count($posts)>0)
                    @foreach ($posts as $post)
                        <li class="d-flex justify-content-between mt-3">
                            <a href="{{route('post.show', $post->id)}}"> {{$post->title}}</a>
                            <div>
                                <a class="btn btn-info" href="{{route('post.edit', $post->id)}}">Edit</a>
                                <a class="btn btn-danger" href="{{route('post.delete', $post->id)}}"
                                   onclick="return confirm('Do you want to delete?')">Delete</a>
                            </div>
                        </li>
                        <hr>
                    @endforeach
                @else
                    <li>No Post Available</li>
                @endif
            </ul>
            --}}
            <div id="posts">

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.2/axios.min.js" integrity="sha512-NCiXRSV460cHD9ClGDrTbTaw0muWUBf/zB/yLzJavRsPNUl9ODkUVmUHsZtKu17XknhsGlmyVoJxLg/ZQQEeGA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>

        document.addEventListener('DOMContentLoaded',function (){
            loadPosts()
            createBtnHandler()
            setDetailsBtnHandler()
        })
        function loadPosts () {
            axios({
                method: 'GET',
                url: "{{route('post.list')}}"
            }).then(res=> res.data)
                .then(res => {
                    console.log('res:',res)
                    let posts=res.data.posts

                    if(res.success){
                        renderPost(posts)
                    }
                    else {
                        let postDom = document.getElementById('posts');
                        postDom.innerHTML="Something went wrong";
                    }

                }

                )

                .catch(err => console.log('err: ', err))
        }
        function renderPost(posts){
            let postDom = document.getElementById('posts');
            postDom.innerHTML=""
            if(posts.length===0){
                postDom.innerHTML='No post available';
            }
            else{
                for(let i=0;i<posts.length;i++){
                    appendpost(posts[i],i);
                }
            }
        }

        function appendpost(post,index){
            let postList = document.getElementById('posts');
            postList.insertAdjacentHTML('beforeend', `
                   <div class="post-item d-flex justify-content-between">
                        <div class="post-title">
                            <span class="details-btn cursor-pointer" data-id="${id}">${post.title}</span>
                        </div>
                        <div class="post-actions">
                            <span  class="btn btn-info edit-btn" data-id="${id}">Edit</span>
                            <span href="" class="btn btn-danger delete-btn" data-id="${id}">Delete</span>
                        </div>
                    </div>
`);
        }
        function createBtnHandler()
        {
            const  createBtn=document.getElementById('create_btn');
            createBtn.addEventListener('click',()=>{

                showCreateForm()
        })
        }
        function showCreateForm()
        {
           let formCard=document.getElementById('create_Form_Card')
            if(!formCard)
            {
                const createDom=document.getElementById('container')
               createDom.insertAdjacentHTML('beforeend', `
<div class="card create_form_Card"id="create_Form_Card">
    <div class="card-header">
        Create Post

    </div>
    <div class="card-body">
        <div class="form-group">
            <labe>Title</labe>
            <input type="text" name="title" id="title" class="form-control" placeholder="Enter post title"
                   value="">
             <span class="text-danger"><strong id="title_validation_error"></strong></span>

        </div>
        <div class="form-group mt-3">
            <labe>Description</labe>
            <textarea name="content" id="content" class="form-control" rows="3"
                      placeholder="Enter post content"></textarea>
                <span class="text-danger"><strong id="content_validation_error"></strong></span>

        </div>
        <button class="btn btn-primary mt-3" id="create_form_submit_btn">Submit</button>
    </div>

</div>
`)
                handleCreateFormSubmitBtn()
            }
        }
        function  handleCreateFormSubmitBtn()
        {
            const createFormSumitBtn=document.getElementById('create_form_submit_btn')
            createFormSumitBtn.addEventListener('click',()=>{
                const title=document.getElementById('title').value
                const content=document.getElementById('content').value
                const titleValidationError=document.getElementById('content')
                const contentValidationError=document.getElementById('content')

                titleValidationError.innerHTML=""
                contentValidationError.innerHTML="";
                    axios({
                        method: 'POST',
                        url: "{{route('post.store')}}",
                        data:{
                            _token:"{{csrf_token()}}",
                            title,
                            content
                        }
                    }).then(res=> res.data)
                        .then(res => {console.log('res:', res)
                        if(res.success)
                        {
                            const  formCard=document.getElementById('create_Form_Card')
                            if(formCard) formCard.remove()
                            loadPosts()
                        }
                        }

                        )
                        .catch(err => {
                    console.log('err: ', err)
                            if(err.response.data.errors){
                                showValidationError(err.response.data.errors)
                            }
                })

            })
        }
        function showValidationError(errors){
            Object.keys(errors).forEach(key=>{
                const dom=document.getElementById(key+'_validation_error')
                dom.innerHTML=errors[key][0]
            })

        }
        function setDetailsBtnHandler(){
            let detailsbtns=document.querySelectorAll('.details-btn')
            for(let i=0;i<detailsbtns.length;i++){
                detailsbtns[i].addEventListener('click',function (){

                    let post=detailsbtns[i].getAttribute('data-id')
                    
                })
            }
        }

    </script>
@endsection


