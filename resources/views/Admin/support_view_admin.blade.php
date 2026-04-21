@extends('Admin.admin_header')
@section('title', 'vishwastha   | Support')
@section('content')
<style>
    .direct-chat-list{
        background-color: #2a2a2a;
    }
    .direct-chat-messages{
        height:500px !important;
    }
    .direct-chat-warning .right>.direct-chat-text {
        background: #4221fd !important;
        border-color: #4221fd !important;
        color: #ffffff !important;
    }  
    .direct-chat-warning .right>.direct-chat-text::after, .direct-chat-warning .right>.direct-chat-text::before {
        border-left-color: #4221fd !important;
    } 
    .contacts-list-img,.direct-chat-img{
        width: 40px; 
        height: 40px; 
        object-fit: cover;
    }
    
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Support</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Support</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    @if(isset($users) && $users->isNotEmpty())
                        <div class="direct-chat-list">
                            <ul class="contacts-list">
                                @foreach ($users as $user)
                                    <li>
                                        <a href="{{ route('send_message_admin', ['userId' => $user->id]) }}">
                                            <img class="contacts-list-img" 
                                                src="{{ $user->user_image ? asset($user->user_image) : asset('assets/dist/img/user4-128x128.jpg') }}" 
                                                alt="User Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    {{ $user->name }}
                                                    <small class="contacts-list-date float-right">
                                                        {{ $user->latestMessage?->created_at->format('m/d/Y h:i A') ?? 'N/A' }}
                                                    </small>
                                                    @if(($unreadMessageCounts[$user->id] ?? 0) == 0)
                                                    @else
                                                    <span class="badge badge-info right">
                                                        {{  $unreadMessageCounts[$user->id] ?? 0}}
                                                    </span>
                                                    @endif
                                                </span>
                                                <span class="contacts-list-msg">
                                                    {{ Str::limit($user->latestMessage?->message ?? 'No message yet', 50) }}
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <!-- /.contacts-list -->
                        </div>
                    @endif

                </div>
                <div class="col-md-8">
                    @if ($selectedUser)
                        <div class="card direct-chat direct-chat-warning">
                            <div class="card-header">
                                <h3 class="card-title">Chat with {{ $selectedUser->name }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="direct-chat-messages">
                                    @foreach ($messages as $message)
                                        <div class="direct-chat-msg {{ $message->msg_from_id == $loggedUser->id ? 'right' : '' }}">
                                            <div class="direct-chat-infos clearfix">
                                                <span class="direct-chat-timestamp {{ $message->msg_from_id == $loggedUser->id ? 'float-left' : 'float-right' }}">
                                                    {{ $message->created_at->format('h:i A') }}
                                                </span>
                                            </div>
                                            <img class="direct-chat-img" 
                                                src="{{ $message->msg_from_id == $loggedUser->id 
                                                    ? asset($adminImage) 
                                                    : asset($selectedUser->user_image) }}" 
                                                alt="User Image">
                                            <div class="direct-chat-text">
                                                {{ $message->message }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-footer">
                                <form action="{{ route('send_message_admin', ['userId' => $selectedUser->id]) }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="message" placeholder="Type Message ..." class="form-control" required>
                                        <span class="input-group-append">
                                            <button type="submit" class="btn btn-info">Send</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <p class="text-muted text-center">Select a user to view their messages.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('footer')
    @if(session()->has('success'))
    <script>Swal.fire({
        position: "top-center",
        icon: "success",
        title: "{{ session()->get('success') }}",
        showConfirmButton: false,
        timer: 1500
    });</script> 
    @endif
    <script>
        $(document).ready(function() {
            const teamLink = $('.nav-link.support');
            if (teamLink.length) {
                teamLink.addClass('active');
            }
        });
    </script>
@endsection