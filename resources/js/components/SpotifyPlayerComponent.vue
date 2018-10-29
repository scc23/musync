<template>
    <div class="col-md-4">
    	<div id="controls">
    		<form method="POST" action="/updateState">
	    		<div class="btn-group">
			        <button type="button" v-on:click="playButton">
			  			play
					</button>
					<button type="button" v-on:click="stopButton">
			  			stop
					</button>
				</div>
			</form>
		</div>
    </div>
</template>

<script>
	var SpotifyWebApi = require('spotify-web-api-js');
	var spotifyApi = new SpotifyWebApi();

	export default {
		props: {
			"accessToken": String,
			"csrfToken": String
		},

		data() {
			return {}
		},

		methods: {
			playButton() {
				console.log("play button is clicked");
				spotifyApi.setAccessToken(this.accessToken);
				spotifyApi.play({"uris": ["spotify:track:56y9Vdi0PWuDqjz6RQ2K93"]}, function(err, data) {
					if (err) console.error(err);
					else console.log(data);
				});

				// Send request to server
				$.ajax({
					type: "POST",
					url: "/updateState",
					data: {_token: this.csrfToken},
					success:function() {
						console.log("data sent to server!");
					},
					error:function(data, textStatus, errorThrown) {
						console.log(data);
					}
				});
			},
			stopButton() {
				console.log("stop button is clicked");
				spotifyApi.setAccessToken(this.accessToken);
				spotifyApi.pause(function(err, data) {
					if (err) console.error(err);
					else console.log(data);
				});
			},
		},

		computed: {
			//
		}
	}
</script>