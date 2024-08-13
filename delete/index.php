<!DOCTYPE html>
<html lang="en">

<head>

	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="description" content="nostr.build" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<script src="/scripts/dist/delete.js?v=923ece1562d41b28cfd727b7a87dd0fa" defer></script>

		<link rel="stylesheet" href="/styles/index.css?v=7ff4472e93e9719e0eac60b75646b485" />
		<link rel="stylesheet" href="/styles/header.css?v=19cde718a50bd676387bbe7e9e24c639" />

		<link rel="icon" href="/assets/nb-logo-color-w.png" />
		<link href="/styles/twbuild.css?v=d6c183f667383307839e6faaff6b9067" rel="stylesheet">

		<title>nostr.build Delete Media</title>
	</head>
</head>

<body>
	<header class="header">
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/components/mainnav.php'; ?>
	</header>
	<main>
		<h1 class="my-2 text-2xl font-bold text-nbpurple-100">Delete Free upload media</h1>
		<div x-data="deleteMediaComponent" class="w-full">
			<form @submit.prevent="deleteMedia(filename)" class="w-full">
				<label for="filename" class="block text-sm font-medium leading-6 text-nbpurple-100">URL, hash or filename</label>
				<div class="mt-2 w-full px-2 flex flex-col items-center">
					<input type="text" name="filename" id="filename" x-model="filename" class="px-1 block w-full rounded-md border-0 py-1.5 text-nbpurple-900 shadow-sm ring-1 ring-inset ring-nbpurple-300 placeholder:text-nbpurple-400 focus:ring-2 focus:ring-inset focus:ring-nbpurple-600 sm:text-sm sm:leading-6" placeholder="8c26367dfe7ca4772336b9090e5dbf7dfce3eab3e3296c64b0379b9055350994.jpg">
					<button type="submit" :disabled="isLoading" class="mt-2 rounded-md bg-nbpurple-50 px-3.5 py-2.5 text-sm font-semibold text-nbpurple-600 shadow-sm hover:bg-nbpurple-100">
						<span x-show="!isLoading">Delete (NIP-07)</span>
						<span x-show="isLoading" class="flex items-center justify-center">
							<svg class="animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
								<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
								<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
							</svg>
							Processing...
						</span>
					</button>
				</div>
			</form>
		</div>
	</main>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'; ?>
</body>

</html>