@extends('Admin.admin_header')
@section('title', 'vishwastha   | Support')
@section('content')
<style>
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
    .direct-chat-img{
        width: 40px; 
        height: 40px; 
        object-fit: cover;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
                <div class="col-md-8" style="margin: 0 auto;">
                  <!-- DIRECT CHAT -->
                  <div class="card direct-chat direct-chat-warning">
                    <div class="card-header">
                      <h3 class="card-title">Direct Chat</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="direct-chat-messages">
                            @foreach ($messages as $message)
                                <div class="direct-chat-msg {{ $message->msg_from_id == auth()->id() ? 'right' : '' }}">
                                    <div class="direct-chat-infos clearfix">
                                        {{-- <span class="direct-chat-name {{ $message->msg_from_id == auth()->id() ? 'float-right' : 'float-left' }}">
                                            {{ $message->msg_from_id }}
                                        </span> --}}
                                        <span class="direct-chat-timestamp {{ $message->msg_from_id == auth()->id() ? 'float-left' : 'float-right' }}">
                                            {{ $message->created_at->format('h:i A') }}
                                        </span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="{{ $message->msg_from_id == $loggedUserId
                                                    ? asset($images['user']) 
                                                    : asset($images['superadmin']) }}" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        {{ $message->message }}
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->
                            @endforeach
                        </div>
                        
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                      <form action="{{ route('send_message') }}" method="POST">
                        @csrf
                        <div class="input-group">
                          <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                          <span class="input-group-append">
                            <button type="submit" class="btn btn-info">Send</button>
                          </span>
                        </div>
                      </form>
                    </div>
                    <!-- /.card-footer-->
                  </div>
                  <!--/.direct-chat -->
                </div>
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