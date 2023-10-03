<?php
// Include config, session, and Permission class files
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/session.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/SiteConfig.php';

global $link;

// Filter based on what we allow in views, potentially extending to allow adult content in the future
$allowed_views = array('img', 'gif', 'vid');

$view_type = isset($_GET['k']) && in_array($_GET['k'], $allowed_views) ? $_GET['k'] : 'img';

$sql = match ($view_type) {
	'gif' => "SELECT * FROM uploads_data WHERE approval_status = 'approved' AND file_extension = 'gif' AND type = 'picture' ORDER BY upload_date DESC LIMIT 50",
	'vid' => "SELECT * FROM uploads_data WHERE approval_status='approved' AND type='video' ORDER BY upload_date DESC LIMIT 12",
	default => "SELECT * FROM uploads_data WHERE approval_status = 'approved' AND file_extension IN ('jpg', 'jpeg', 'png', 'webp') AND type = 'picture' ORDER BY upload_date DESC LIMIT 200",
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="nostr.build" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<link rel="stylesheet" href="/styles/index.css?v=3" />
	<link rel="stylesheet" href="/styles/profile.css?v=3" />
	<link rel="stylesheet" href="/styles/header.css?v=4" />
	<link rel="stylesheet" href="/styles/twbuild.css?v=48" />
	<link rel="icon" href="/assets/0.png">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/css/lightgallery-bundle.min.css" integrity="sha512-nUqPe0+ak577sKSMThGcKJauRI7ENhKC2FQAOOmdyCYSrUh0GnwLsZNYqwilpMmplN+3nO3zso8CWUgu33BDag==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/video.js/8.5.3/alt/video-js-cdn.min.css" integrity="sha512-OxFNWAvUrErw1lQmH+xnjFJZePnr6zA0/H/ldxoXaYUn3yHcII7RpB6cfysY0rhxRZeCIUzQIECLOCXIYrfOIw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/lightgallery.umd.min.js" integrity="sha512-6vFONv+JJD01XArGGqxABRY3Vsm8tKuemThmZYfha9inGIuqPU5OgZP1QizBf0Y3JGPnrofy3jokdebgYNNhEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<!--
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/plugins/share/lg-share.umd.min.js" integrity="sha512-LqLPMRjHAY1Y3A8R5bh8IeV/rjSdBOu87nlqOq91UQsT7Yece+FLvKuUDju9lqdLzzDCoisUDCLbue6zSrnDgg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/plugins/pager/lg-pager.umd.min.js" integrity="sha512-ZZ4nNtKuI4D5yMkBjOjFrIFka7RyoKOam4UpdzMFi3zp6Z9vCjolKx3rhjkGQ3aL7B0q4tSRM2HOFRwpYCNUPg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/plugins/mediumZoom/lg-medium-zoom.umd.min.js" integrity="sha512-xwwdYFH/uD+GZ8QhMEhDNBKMne9D43tMNRnLM0bnpYwlIw5QItMyGmiZ9cZ4CoZNfu70orn3KFQpi5OijYsIrA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
-->
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/plugins/autoplay/lg-autoplay.umd.min.js" integrity="sha512-GtFOSYOB4Gx5+0hQxi/nVFJk77Tvmmgs/Kdbl4PZLjiZ8RBRMiKU2r33gsdn19r4Nlnx9lDqKf8ZdOSNwdgUtw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/plugins/fullscreen/lg-fullscreen.umd.min.js" integrity="sha512-xmNLmAH+RvR1Bbdq1hML9/Hqp3Uvf6++oZbc6h+KVw2CpJE0oOPIc0zV5nbuTLlOU+1pLOIPlBvcrVqUUXZh7w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/plugins/hash/lg-hash.umd.min.js" integrity="sha512-MQKJS2hbR8dmwpFNNsZ35od470xx/5FwNvyzqa1yc4fOHLpWVQUdMJWcRReqtmHiWNlP8DVwEBw2v2d67IfsMg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/plugins/thumbnail/lg-thumbnail.umd.min.js" integrity="sha512-hdzLQVAURjMzysJVkWaKWA2nD+V6CcBx6wH0aWytFnlmgIdTx/n5rDWoruSvK6ghnPaeIgwKuUESlpUhat2X+Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/plugins/video/lg-video.umd.min.js" integrity="sha512-olksMIiITctwLVsKDH2fm9nylHHYzK2v/bIY+LzBO9GAw9A44MBjYaJGm/2eIbhTtXZXdXQUoS17HoV2rI+fFA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/plugins/zoom/lg-zoom.umd.min.js" integrity="sha512-XXCpe8fRNmJzU9JVpJbjXIg4SpUeWcsLjeIFEnjQeD+2Y4Einh1spMPeN/1XcnfjYE+ebBY1f/U/Up7vx8+PEA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/video.js/8.5.3/video.min.js" integrity="sha512-wUWE15BM3aEd9D+01qFw8QdCoeB/wDYmOOqkgeeKiYXE+kiPOboLcOES+1lJMa5NiPBPBQenZYoOWRhf5jv4sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<script defer type="module" src="/scripts/fw/blurhash-img.js?v=0.2.1"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			lightGallery(document.getElementById('lightgallery'), {
				plugins: [lgZoom, lgThumbnail, lgAutoplay, lgFullscreen, lgHash, /*lgPager, lgShare,*/ lgVideo /*, lgMediumZoom*/ ],
				speed: 500,
				thumbnail: true,
				videojs: true,
				videojsOptions: {
					muted: false,
				},
				autoplayVideoOnSlide: true,
				gotoNextSlideOnVideoEnd: true,
			});

			function observeImages() {
				const imageContainers = document.querySelectorAll(".image-container:not(.observed)");

				const observer = new IntersectionObserver((entries, observer) => {
					entries.forEach(entry => {
						if (entry.isIntersecting) {
							const imgContainer = entry.target;
							const img = imgContainer.querySelector("img.lazy-loaded");
							const loader = imgContainer.querySelector('[role="status"]'); // Assuming role="status" is unique within the container

							// Show the loader
							loader.style.opacity = "1";
							loader.style.zIndex = "2";

							const handleLoad = function() {
								const blurhashSibling = img.nextElementSibling;

								if (blurhashSibling && blurhashSibling.tagName.toLowerCase() === "blurhash-img") {
									//blurhashSibling.remove();
									blurhashSibling.style.height = "0";
									blurhashSibling.style.visibility = "hidden";
									blurhashSibling.style.opacity = "0";
									blurhashSibling.style.zIndex = "-1";

									img.style.height = "auto";
									img.style.visibility = "visible";
									img.style.opacity = "1";
									img.style.zIndex = "1"; // add this line to bring the image forward

									// Hide the loader
									loader.style.opacity = "0";
									loader.style.zIndex = "-1";
								}

								img.removeEventListener("load", handleLoad);
							};

							img.addEventListener("load", handleLoad);

							if (img.complete) {
								img.dispatchEvent(new Event("load"));
							}

							observer.unobserve(imgContainer);
							imgContainer.classList.add("observed"); // Mark this image as observed
						}
					});
				});

				imageContainers.forEach(img => {
					observer.observe(img);
				});
			}

			observeImages(); // Initial run
			// If you're dynamically loading new content, you can run `observeImages()` again
		});
	</script>

	<title>nostr.build - Free View</title>
	<style>
		[role="status"] {
			opacity: 0;
			transition: opacity 0.3s ease;
			z-index: -1;
		}

		.lazy-loaded {
			height: 0;
			visibility: hidden;
			opacity: 0;
			transition: opacity 0.6s ease-in-out;
			z-index: -1;
		}

		blurhash-img {
			--aspect-ratio: 4/6;
			/* This is just a default, your PHP will override this */
		}
	</style>
</head>

<body>
	<header class="header">
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/components/mainnav.php'; ?>
	</header>

	<main>
		<section class="title_section">
			<h1>
				<span>Free View</span>
			</h1>
			<a href='?k=gif'><button class="donate_button">GIFs</button></a>
			<a href='?k=img'><button class="donate_button">Images</button></a>
			<a href='?k=vid'><button class="donate_button">Videos</button></a><BR>
		</section>

		<?php
		$stmt = $link->prepare($sql);
		$stmt->execute();

		$result = $stmt->get_result();
		?>
		<div id="lightgallery" class="gap-2 columns-2 md:columns-3 lg:columns-4 xl:columns-6 w-screen px-2 md:px-4">
			<?php while ($row = $result->fetch_assoc()) : ?>
				<?php
				$filename = $row['filename'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$thumbnail_path = htmlspecialchars(SiteConfig::getThumbnailUrl($view_type === 'vid' ? 'video' : 'image') . $filename);
				$full_path = htmlspecialchars(SiteConfig::getFullyQualifiedUrl($view_type === 'vid' ? 'video' : 'image') . $filename);
				$blurhash = htmlspecialchars($row['blurhash']);

				$media_width = empty($row['media_width']) || $row['media_width'] == 0 ? 4 : $row['media_width'];
				$media_height = empty($row['media_height']) || $row['media_height'] == 0 ? 6 : $row['media_height'];
				$aspect_ratio = "{$media_height}/{$media_width}";

				// Responsive image sizes
				$resolutionToWidth = [
					"240p"  => "426",
					"360p"  => "640",
					"480p"  => "854",
					"720p"  => "1280",
					"1080p" => "1920",
				];
				$srcset = [];
				foreach ($resolutionToWidth as $resolution => $width) {
					$srcset[] = htmlspecialchars(SiteConfig::getResponsiveUrl($view_type === 'vid' ? 'video' : 'image', $resolution) . $filename . " {$width}w");
				}
				$srcset = implode(", ", $srcset);
				$sizes = '(max-width: 426px) 100vw, (max-width: 640px) 100vw, (max-width: 854px) 100vw, (max-width: 1280px) 50vw, 33vw';
				// video poster placeholder, until we have real ones: https://cdn.nostr.build/assets/video/jpg/video-poster@0.25x.jpg
				$lgSrc = match ($view_type) {
					'vid' => 'data-video=\'{"source": [{"src":"' . $full_path . '", "type": "video/mp4"}], "attributes": {"preload": "auto", "playsinline": true, "controls": true}}\' data-poster="https://cdn.nostr.build/assets/video/jpg/video-poster@0.75x.jpg"',
					'gif' => 'data-src="' . $full_path . '"',
					default => 'data-responsive="' . $srcset . '" data-src="' . $full_path . '"',
				};
				?>
				<div class="relative group break-inside-avoid" <?= $lgSrc ?>>
					<a href="<?= $full_path ?>" target="_blank" rel="noopener noreferrer">
						<?php if ($view_type === 'vid') : ?>
							<!-- A video poster is required for lightgallery to work -->
							<img src="https://cdn.nostr.build/assets/video/jpg/video-poster@0.5x.jpg" alt="video poster" style="display: none;" />
							<video class="mb-2" controls preload="auto">
								<source src="<?= $thumbnail_path ?>" type="video/mp4">
							</video>
						<?php else : ?>
							<div class="image-container mb-2">
								<?php if ($view_type === 'gif') : ?>
									<img loading="lazy" class="w-full lazy-loaded" src="<?= $thumbnail_path ?>" alt="image" />
								<?php else : ?>
									<img loading="lazy" class="w-full lazy-loaded" src="<?= $thumbnail_path ?>" srcset="<?= $srcset ?>" sizes="<?= $sizes ?>" alt="image" />
								<?php endif; ?>
								<blurhash-img class="w-full" hash="<?= $blurhash ?>" style="--aspect-ratio: <?= $aspect_ratio ?>">
								</blurhash-img>
								<div role="status" class="absolute inset-0 grid place-items-center">
									<svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" />
										<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill" />
									</svg>
									<span class="sr-only">Loading...</span>
								</div>
							</div>
						<?php endif; ?>
					</a>
				</div>
			<?php endwhile; ?>
		</div>
		<?php
		$link->close();
		?>
	</main>
	<a class="ref_link pb-4" style="font-size: x-large;" href="https://nostr.build/signup/new"> Get access to all 500k+ Videos, Gifs and images HERE!</a>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'; ?>
</body>

</html>