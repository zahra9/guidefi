@extends('layouts.dashboard')

@section('template_title')
	{{ $user->name }}'s Account
@endsection

@section('template_fastload_css')
@endsection

@section('header')
	<small>
		{{ trans('profile.accountTitle',['username' => $user->name]) }}
	</small>
@endsection

@section('breadcrumbs')

	<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
		<a itemprop="item" href="{{url('/')}}">
			<span itemprop="name">
				{{ trans('titles.app') }}
			</span>
		</a>
		<i class="material-icons">chevron_right</i>
		<meta itemprop="position" content="1" />
	</li>

	<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="active">
		<a itemprop="item" href="{{ url('/account/') }}" class="hidden">
			<span itemprop="name">
				{{ trans('titles.account') }}
			</span>
		</a>
		{{ trans('titles.account') }}
		<meta itemprop="position" content="2" />
	</li>

@endsection

@section('content')

<div class="mdl-card mdl-shadow--2dp mdl-cell margin-top-0-tablet-important mdl-cell--12-col mdl-cell--12-col-tablet mdl-cell--12-col-desktop weather-container">
    <div class="mdl-card__title mdl-color--primary mdl-color-text--white header-container">
        <h2 class="mdl-card__title-text">
        	<span class="header-title">
				Subscription
        	</span>
        </h2>
    </div>





			</div>


		</div>

    </div>




</div>










	<style type="text/css" media="screen">
		.header-container {

		}
		.account-header-password {

		}
		.account-header-delete {
			display: none;
		}
	</style>
@endsection
@section('footer_scripts')


		<script>

		$(document).ready(function() {

			function tabHeaders() {
				var titleContainer = $('.header-container');
				var titleContent = $('.header-title');
				var trigger = $('.mdl-tabs__tab');
				var delTriggerClass = 'del-tab';
				var pwTriggerClass = 'pw-tab';
				var deleteClass = "mdl-color--red-400";
				var pwClass = "mdl-color--yellow-800";
				var defaultClass = "mdl-color--primary";
				var activeClass = "is-active";
				var title;

				trigger.click(function() {

					var self = $(this);
					title = self.text();

					titleContainer.removeClass(defaultClass + ' ' + deleteClass + ' ' + pwClass);

				    switch (true) {
				      case self.hasClass(delTriggerClass):
				        titleContainer.addClass(deleteClass);
				        break;
				      case self.hasClass(pwTriggerClass):
				        titleContainer.addClass(pwClass);
				        break;
				      default:
						titleContainer.addClass(defaultClass);
				        break;
				    }
					titleContent.html(title);
				});
			}
			tabHeaders();
		});

	</script>


@endsection
