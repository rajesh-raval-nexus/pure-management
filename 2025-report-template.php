<?php
/* Template Name: Member Workout 2025 */

get_header();
$everything_is_valid = false;

global $pure_member_profile;

$table_column_name_prefix = strtolower(PURE_REGION);

if(is_logged_in() && !empty($pure_member_profile)){
    $rssid = $pure_member_profile['mbo_rssid'];
    $everything_is_valid = true;
}elseif (isset($_GET['data']) && !empty($_GET['data']) && isset($_GET['code']) && !empty($_GET['code'])) {
    $secret = '2025-year-end-report';

    // Decode the `data` parameter (Base64 decoding)
    $data_param = filter_input(INPUT_GET, 'data', FILTER_SANITIZE_STRING);
    $decoded_data = base64_decode($data_param);
    $data_json = json_decode($decoded_data, true);

    // Ensure `data` contains the required keys
    if (isset($data_json['rssid'])) {
        $rssid = $data_json['rssid'];

        // Validate `code`
        $generated_code = md5($secret . $data_param);

        if ($generated_code === $_GET['code']) {
            // All conditions are met; display data for the user
            // Retrieve data based on `rssid` and display the report

            $everything_is_valid = true;
        } else {
            wp_redirect(get_site_url_localized('/login/'));
            exit;
        }
    } else {
        wp_redirect(get_site_url_localized('/login/'));
        exit;
    }
} else {
    wp_redirect(get_site_url_localized('/login/'));
    exit;
}

if($everything_is_valid){

    function get_class_by_title($title, $post_type) {
        $slug = sanitize_title($title);
        $posts = get_posts([
            'name'        => $slug,
            'post_type'   => $post_type,
            'post_status' => 'publish',
            'numberposts' => 1,
        ]);

        return $posts ? $posts[0] : null;
    }

    $language = get_language_code();
    $page_id = get_the_ID();


    global $wpdb;

    $table = $wpdb->prefix . 'pure_user_annual_data_2025';
    $table2 = 'hk_4__total_workout_days_xxx';
    $table3 = 'hk_12__month_with_most_classes_attended_or_visited_to_pure';
    $table4 = 'hk_28__no__of_reset_session_you_joined';

    // Use prepare only for values, not table names
    $sql = $wpdb->prepare(
        "SELECT * FROM $table WHERE RSSID LIKE %s",
        $rssid
    );

    $results = $wpdb->get_results($sql, ARRAY_A);

    $sql2 = $wpdb->prepare(
        "SELECT * FROM $table2 WHERE RSSID LIKE %s",
        $rssid
    );

    $results2 = $wpdb->get_results($sql2, ARRAY_A);

    $sql3 = $wpdb->prepare(
        "SELECT * FROM $table3 WHERE RSSID LIKE %s",
        $rssid
    );

    $results3 = $wpdb->get_results($sql3, ARRAY_A);

    $sql4 = $wpdb->prepare(
        "SELECT * FROM $table4 WHERE RSSID LIKE %s",
        $rssid
    );

    $results4 = $wpdb->get_results($sql4, ARRAY_A);


    // Assuming you want to process the first item in the array
    if (!empty($results[0])) {
        $current_language = get_language_code();
        $no_data_available_msg = ($current_language == 'en') ? 'Not available' : '不適用';
        $no_msg = ($current_language == 'en') ? 'No' : '不';
        $not_yet_msg = ($current_language == 'en') ? 'No, not yet.' : '還沒有。';
        $share_btn_lbl = ($current_language == 'en') ? 'Share it now' : '立即分享';
        $na_msg = ($current_language == 'en') ? 'N/A' : '不適用';
        $default_teacher_img = get_stylesheet_directory_uri(). "/assets/images/workout/teacher-default-avatar.jpg";
        $user_display_name = $results[0][$table_column_name_prefix.'_07_longest_consec_member_name'];

        // Might Required
        $hour_label = ($current_language == 'en') ? 'hours' : '小時';
        $minutes_label = ($current_language == 'en') ? 'minutes' : '分鐘';

        $yoga_class_comparison_last_year = ($results[0]['yoga_class_comparison_last_year']) ?: 0;
        $yoga_class_comparison_2023_year = ($results[0]['yoga_class_2023']) ?: 0;
        $top_yoga_classes = ($results[0]['top_yoga_classes']) ?: $no_data_available_msg;
        $top_yoga_teachers = ($results[0]['top_yoga_teachers']) ?: $no_data_available_msg;
        $gym_class_comparison_last_year = ($results[0]['gym_class_comparison_last_year']) ?: 0;
        $gym_class_comparison_2023_year = ($results[0]['gym_class_2023']) ?: 0;
        $top_fitness_classes = ($results[0]['top_fitness_classes']) ?: $no_data_available_msg;
        $top_fitness_instructors = ($results[0]['top_fitness_instructors']) ?: $no_data_available_msg;
        $fitness_workshop_hours = ($results[0]['fitness_workshop_hours']) ?: $no_data_available_msg;
        $yoga_workshop_hours = ($results[0]['yoga_workshop_hours']) ?: $no_data_available_msg;

        $month_display = $raw_month
            ? ($current_language == 'en'
                ? date("F Y", strtotime($raw_month . "-01"))
                : strftime("%Y年 %B", strtotime($raw_month . "-01"))
            )
            : $no_msg;
    }
    ?>
<?php
    $workout = get_field("hero_banner", $page_id);
    $lan_title = $workout['title'];
    // $title = ($lan_title[$language]) ? $lan_title[$language] : $lan_title['en'];
    $title = ($current_language == 'en') ? 'Your 2025 PURE Holistic Wellness Wrapped' : 'PURE身心旅程 2025年度回顧';

    $lan_desc = $workout['short_description'];
    $sdescription = ($lan_desc[$language]) ? $lan_desc[$language] : $lan_desc['en'];
    $sdescription = ($current_language == 'en') ? "Saluting your year of movement. See how far you've come on your PURE journey—track your progress, celebrate achievements, and get inspired to smash many more milestones ahead! This is how we Turn Life ON!" : "回顧這一年，為來年注入更多動力！
記錄每一份努力，慶祝每一份成就，激發更強大的自己，PURE 與您Turn Life On點亮•生活！";

    $bg_img = $workout['banner'];
    $desktop_banner = get_stylesheet_directory_uri(). "/assets/images/workout/workout-hero-banner.jpg";
    $mobile_banner = get_stylesheet_directory_uri(). "/assets/images/workout/workout-hero-banner-m.jpg";
    ?>

<!-- New Design -->
<!-- Hero section -->
<section class="cmw-1320 workot-hero d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h1 class="title-h1 mb-4">
                    Your 2025 Pure Holistic<br>
                    Wellness Wrapped
                </h1>

                <p class="desc-text">
                    Saluting your year of movement. See how far you've come on your PURE journey—track your progress,
                    celebrate achievements, and get inspired to smash many more milestones ahead!
                    This is how we Turn Life ON!
                </p>
            </div>
            <div class="col-md-5 position-relative text-center">
                <img src="<?= get_image_url('workout-2026/beautiful-sportive-girl.webp') ?>" class="img-fluid">
                <span class="hexagon hex-teal">
                    <img src="<?= get_image_url('workout-2026/hexagon-teal.svg') ?>" class="img-fluid">
                </span>
            </div>

        </div>
    </div>
</section>

<section class="journey-report">
    <div class="journey-inner">
        <span class="hexagon hex-pink">
            <img src="<?= get_image_url('workout-2026/hexagon-pink.svg') ?>" class="img-fluid">
        </span>
        <div class="journey-image">
            <img src="<?= get_image_url('workout-2026/your-journey.webp') ?>" alt="person" />
        </div>

        <div class="journey-content">
            <h2 class="title-h2">Your Journey With Us!</h2>

            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Days with PURE</div>
                    <div class="stat-value">443</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Total Friends Referred to PURE</div>
                    <div class="stat-value">0</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Total Active Days in 2025</div>
                    <div class="stat-note">PURE Cardholders averaged 52 PURE days in 2025.</div>
                    <div class="stat-value">66</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">First Class of 2025</div>
                    <div class="stat-value">Hot Hatha 1</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">First Location Visited in 2025</div>
                    <div class="stat-value">Yoga - Pacific Place</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Longest PURE Streak</div>
                    <div class="stat-note">Keep moving—you're on your way!</div>
                    <div class="stat-value">4</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cmw-1320 active-hours py-80 overflow-hidden">
    <span class="hexagon hex-teal">
        <img src="<?= get_image_url('workout-2026/hexagon-teal.svg') ?>" class="img-fluid">
    </span>
    <span class="hexagon hex-pink">
        <img src="<?= get_image_url('workout-2026/hexagon-pink.svg') ?>" class="img-fluid">
    </span>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4 mb-md-5">
                <h2 class="title-h2">Your Active Hours</h2>
            </div>
        </div>
        <div class="row">
            <div class="pill-stats text-center">
                <div class="pill-item">
                    <div class="pill-wrap">
                        <div class="pill-fill" style="--fill:16%;">
                            <span class="wave"></span>
                        </div>
                    </div>
                    <p class="title-h3 mt-3 mb-2">Morning (6–9am)</p>
                    <p class="title-h4">16%</p>
                </div>

                <div class="pill-item">
                    <div class="pill-wrap">
                        <div class="pill-fill full" style="--fill:100%;">
                            <span class="wave"></span>
                        </div>
                    </div>
                    <p class="title-h3 mt-3 mb-2">Daytime (9am–5pm)</p>
                    <p class="title-h4">125%</p>
                </div>

                <div class="pill-item">
                    <div class="pill-wrap">
                        <div class="pill-fill" style="--fill:24%;">
                            <span class="wave"></span>
                        </div>
                    </div>
                    <p class="title-h3 mt-3 mb-2">Evening / Night (after 5pm)</p>
                    <p class="title-h4">24%</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cmw-1320 most-active-month py-80">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="title-h2 mb-4 text-center">Your most active month</h2>
                <div class="month-bg position-relative">
                    <img src="<?= get_image_url('workout-2026/active-month-bg.webp') ?>" alt="Your most active month"
                        class="img-fluid w-100">
                    <div class="month-info text-center">
                        <div class="position-relative">
                            <img src="<?= get_image_url('workout-2026/calendar.svg') ?>" alt="calendar">
                            <p class="month-h3">Nov</p>
                        </div>
                        <p class="title-h4 font-700 text-pink">11 Active Days</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cmw-1320 yoga-sessions sessions-bg py-80">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4 mb-md-5">
                <h2 class="title-h2 text-white">Yoga Classes</h2>
            </div>
        </div>
        <div class="col-12 glass-bx">
            <div class="row align-items-stretch">
                <div class="col-lg-5">
                    <div class="glass-card">
                        <h3 class="title-h3 mb-2 text-black">Class Attended</h3>
                        <p class="title-h4 mb-2">47</p>
                        <p class="p-desc">
                            Omm-azing! Your devotion shines through your action!
                        </p>
                    </div>

                    <div class="glass-card">
                        <h3 class="title-h3 mb-2 text-black">Year-on-year Yoga Comparison</h3>
                        <div class="d-flex gap-5 align-items-center mb-2">
                            <div>
                                <p class="title-h3 mb-2 text-black">2024</p>
                                <p class="title-h4">12</p>
                            </div>
                            <div class="ms-4">
                                <p class="title-h3 mb-2 text-black">2025</p>
                                <p class="title-h4">
                                    47
                                    <svg class="" xmlns="http://www.w3.org/2000/svg" width="18" height="20"
                                        viewBox="0 0 31 37" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M16.1577 37L30.4374 18.5044C30.9436 17.8487 30.8225 16.9067 30.1667 16.4004C29.511 15.8942 28.569 16.0153 28.0627 16.6711L16.0924 32.1755L3.41212 16.6393C2.8883 15.9975 1.94338 15.9018 1.30159 16.4257C0.659789 16.9495 0.564149 17.8944 1.08797 18.5362L16.1577 37Z"
                                            fill="#14E1DC"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M16.1577 21L30.4374 2.50442C30.9436 1.84869 30.8225 0.9067 30.1667 0.400436C29.511 -0.105829 28.569 0.0153389 28.0627 0.671074L16.0924 16.1755L3.41212 0.639286C2.8883 -0.00251198 1.94338 -0.0981503 1.30159 0.425671C0.659789 0.94949 0.564149 1.89441 1.08797 2.53621L16.1577 21Z"
                                            fill="#14E1DC"></path>
                                    </svg>
                                </p>
                            </div>
                        </div>
                        <p class="p-desc">
                            “Yoga is a journey of the self, through the self, to the self.”
                        </p>
                    </div>

                    <div class="glass-card mb-lg-0">
                        <h3 class="title-h3 mb-2 text-black">Top 3 Yoga Classes</h3>
                        <p class="title-h4">
                            Yoga Sculpt<br>
                            Hot Hatha 1<br>
                            Hot Hatha 2
                        </p>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="image-card h-100">
                        <img src="<?= get_image_url('workout-2026/yoga-sessions.webp') ?>" alt="Yoga Class"
                            class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cmw-1320 top-teachers py-80 pb-0">
    <span class="hexagon top-center">
        <img src="<?= get_image_url('workout-2026/hexagon-teal.svg') ?>" class="img-fluid">
    </span>
    <div class="container p-0">
        <div class="row m-0">
            <div class="col-12 text-center mb-4 mb-md-5">
                <h2 class="title-h2">Top 3 Yoga Teachers</h2>
            </div>
        </div>

        <div class="row align-items-end m-0">
            <div class="col-md-4 text-center teacher-card">
                <span class="hexagon hex-right">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid ">
                </span>
                <span class="hexagon hex-left">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid hex-left">
                </span>
                <h3 class="title-h3">Laura Beu</h3>
                <div class="teacher-image">
                    <img src="<?= get_image_url('workout-2026/laura-beu.png') ?>" alt="Laura Beu" class="img-fluid">
                </div>
            </div>

            <div class="col-md-4 text-center teacher-card">
                <span class="hexagon hex-right hex-second">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid ">
                </span>
                <span class="hexagon hex-left hex-second">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid hex-left">
                </span>
                <h3 class="title-h3">Burley Chan</h3>
                <div class="teacher-image">
                    <img src="<?= get_image_url('workout-2026/burley-chan.png') ?>" alt="Burley Chan" class="img-fluid">
                </div>
            </div>

            <div class="col-md-4 text-center teacher-card">
                <span class="hexagon hex-right">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid ">
                </span>
                <span class="hexagon hex-left hex-third">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid hex-left ">
                </span>
                <h3 class="title-h3">Linda Wong</h3>
                <div class="teacher-image">
                    <img src="<?= get_image_url('workout-2026/linda-wong.png') ?>" alt="Linda Wong" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cmw-1320 groupf-sessions sessions-bg py-80">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4 mb-md-5">
                <h2 class="title-h2 text-white">Group Fitness (Gx) Classes</h2>
            </div>
        </div>
        <div class="col-12 glass-bx">
            <div class="row align-items-stretch">
                <div class="col-lg-5">
                    <div class="glass-card">
                        <h3 class="title-h3 mb-2 text-black">Classes Attended</h3>
                        <p class="title-h4 mb-2 text-pink">11</p>
                        <p class="p-desc">Not only setting but raising the bar...awe-inspiring!</p>
                    </div>

                    <div class="glass-card">
                        <h3 class="title-h3 mb-2 text-black">Year-on-year Group Fitness Comparison</h3>
                        <div class="d-flex gap-5 align-items-center mb-2">
                            <div>
                                <p class="title-h3 mb-2 text-black">2024</p>
                                <p class="title-h4 text-pink">0</p>
                            </div>
                            <div class="ms-4">
                                <p class="title-h3 mb-2 text-black">2025</p>
                                <p class="title-h4 text-pink">
                                    11
                                    <svg class="" xmlns="http://www.w3.org/2000/svg" width="18" height="20"
                                        viewBox="0 0 31 37" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M16.1577 37L30.4374 18.5044C30.9436 17.8487 30.8225 16.9067 30.1667 16.4004C29.511 15.8942 28.569 16.0153 28.0627 16.6711L16.0924 32.1755L3.41212 16.6393C2.8883 15.9975 1.94338 15.9018 1.30159 16.4257C0.659789 16.9495 0.564149 17.8944 1.08797 18.5362L16.1577 37Z"
                                            fill="#F00F64"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M16.1577 21L30.4374 2.50442C30.9436 1.84869 30.8225 0.9067 30.1667 0.400436C29.511 -0.105829 28.569 0.0153389 28.0627 0.671074L16.0924 16.1755L3.41212 0.639286C2.8883 -0.00251198 1.94338 -0.0981503 1.30159 0.425671C0.659789 0.94949 0.564149 1.89441 1.08797 2.53621L16.1577 21Z"
                                            fill="#F00F64"></path>
                                    </svg>
                                </p>
                            </div>
                        </div>
                        <p class="p-desc">
                            “Strength grows in those
                            moments when you think you
                            can't go on but you keep going.”
                        </p>
                    </div>

                    <div class="glass-card mb-lg-0">
                        <h3 class="title-h3 mb-2 text-black">Top 3 Group Fitness Classes</h3>
                        <p class="title-h4 text-pink">
                            Pilates<br>
                            Rpm™<br>
                            Just Core
                        </p>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="image-card h-100">
                        <img src="<?= get_image_url('workout-2026/groupf-sessions.webp') ?>" alt="Yoga Class"
                            class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="cmw-1320 top-teachers py-80 pb-0">
    <span class="hexagon top-center">
        <img src="<?= get_image_url('workout-2026/hexagon-pink.svg') ?>" class="img-fluid">
    </span>
    <div class="container p-0">
        <div class="row m-0">
            <div class="col-12 text-center mb-4 mb-md-5">
                <h2 class="title-h2">Your Top 3 Group Fitness (Gx) Instructors</h2>
            </div>
        </div>

        <div class="row align-items-end m-0">
            <div class="col-md-4 text-center teacher-card bg-pink">
                <span class="hexagon hex-right">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid ">
                </span>
                <span class="hexagon hex-left">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid hex-left">
                </span>
                <h3 class="title-h3">Vincci Chu</h3>
                <div class="teacher-image">
                    <img src="<?= get_image_url('workout-2026/vincci-chu.png') ?>" alt="Vincci Chu" class="img-fluid">
                </div>
            </div>

            <div class="col-md-4 text-center teacher-card bg-pink50">
                <span class="hexagon hex-right hex-second">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid ">
                </span>
                <span class="hexagon hex-left hex-second">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid hex-left">
                </span>
                <h3 class="title-h3">Mike McHugh</h3>
                <div class="teacher-image">
                    <img src="<?= get_image_url('workout-2026/mike-mchugh.png') ?>" alt="Mike McHugh" class="img-fluid">
                </div>
            </div>

            <div class="col-md-4 text-center teacher-card bg-pink">
                <span class="hexagon hex-right">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid ">
                </span>
                <span class="hexagon hex-left hex-third">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid hex-left ">
                </span>
                <h3 class="title-h3">Sean Tjahja</h3>
                <div class="teacher-image">
                    <img src="<?= get_image_url('workout-2026/sean-tjahja.png') ?>" alt="Sean Tjahja" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>
<section class="cmw-1320 reformer-sessions sessions-bg py-80">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4 mb-md-5">
                <h2 class="title-h2 text-white">Reformer Pilates Classes</h2>
            </div>
        </div>
        <div class="col-12 glass-bx">
            <div class="row align-items-stretch">
                <div class="col-lg-5">
                    <div class="glass-card">
                        <h3 class="title-h3 mb-2 text-black">Classes Attended</h3>
                        <p class="title-h4 mb-2 text-purple">8</p>
                        <p class="p-desc">Your core control and commitment are equally impressive. Keep rising to the
                            challenge!</p>
                    </div>

                    <div class="glass-card mb-lg-0">
                        <h3 class="title-h3 mb-2 text-black">Year-on-year Reformer Pilates comparison</h3>
                        <div class="d-flex gap-5 align-items-center mb-2">
                            <div>
                                <p class="title-h3 mb-2 text-black">2024</p>
                                <p class="title-h4 text-purple">1</p>
                            </div>
                            <div class="ms-4">
                                <p class="title-h3 mb-2 text-black">2025</p>
                                <p class="title-h4 text-purple">
                                    8
                                    <svg class="" xmlns="http://www.w3.org/2000/svg" width="18" height="20"
                                        viewBox="0 0 31 37" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M16.1577 37L30.4374 18.5044C30.9436 17.8487 30.8225 16.9067 30.1667 16.4004C29.511 15.8942 28.569 16.0153 28.0627 16.6711L16.0924 32.1755L3.41212 16.6393C2.8883 15.9975 1.94338 15.9018 1.30159 16.4257C0.659789 16.9495 0.564149 17.8944 1.08797 18.5362L16.1577 37Z"
                                            fill="#5E4D9B"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M16.1577 21L30.4374 2.50442C30.9436 1.84869 30.8225 0.9067 30.1667 0.400436C29.511 -0.105829 28.569 0.0153389 28.0627 0.671074L16.0924 16.1755L3.41212 0.639286C2.8883 -0.00251198 1.94338 -0.0981503 1.30159 0.425671C0.659789 0.94949 0.564149 1.89441 1.08797 2.53621L16.1577 21Z"
                                            fill="#5E4D9B"></path>
                                    </svg>
                                </p>
                            </div>
                        </div>
                        <p class="p-desc">
                            “Control, precision, persistence—the holy trinity of Reformer Pilates.”
                        </p>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="image-card h-100">
                        <img src="<?= get_image_url('workout-2026/reformer-sessions.webp') ?>"
                            alt="Reformer Pilates Class" class="img-fluid h-100">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="cmw-1320 top-teachers py-80 pb-0">
    <span class="hexagon top-center">
        <img src="<?= get_image_url('workout-2026/hexagon-purple.svg') ?>" class="img-fluid">
    </span>
    <div class="container p-0">
        <div class="row m-0">
            <div class="col-12 text-center mb-4 mb-md-5">
                <h2 class="title-h2">Top 3 Reformer Pilates Teachers</h2>
            </div>
        </div>

        <div class="row align-items-end m-0">
            <div class="col-md-4 text-center teacher-card bg-purple">
                <span class="hexagon hex-right">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid ">
                </span>
                <span class="hexagon hex-left">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid hex-left">
                </span>
                <h3 class="title-h3">Christophe Blanc</h3>
                <div class="teacher-image">
                    <img src="<?= get_image_url('workout-2026/christophe-blanc.png') ?>" alt="Christophe Blanc"
                        class="img-fluid">
                </div>
            </div>

            <div class="col-md-4 text-center teacher-card bg-purple50">
                <span class="hexagon hex-right hex-second">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid ">
                </span>
                <span class="hexagon hex-left hex-second">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid hex-left">
                </span>
                <h3 class="title-h3">Jessica Yeung</h3>
                <div class="teacher-image">
                    <img src="<?= get_image_url('workout-2026/jessica-yeung.png') ?>" alt="Jessica Yeung"
                        class="img-fluid">
                </div>
            </div>

            <div class="col-md-4 text-center teacher-card bg-purple">
                <span class="hexagon hex-right">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid ">
                </span>
                <span class="hexagon hex-left hex-third">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid hex-left ">
                </span>
                <h3 class="title-h3">Lily Mok.</h3>
                <div class="teacher-image">
                    <img src="<?= get_image_url('workout-2026/lily-mok.png') ?>" alt="Lily Mok" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>
<section class="cmw-1320 training-wrap py-80">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="training-inner position-relative mb-4">
                    <span class="hexagon">
                        <img src="<?= get_image_url('workout-2026/hexagon-teal.svg') ?>" class="img-fluid">
                    </span>
                    <h2 class="title-h2 text-capitalize mb-4">Private Yoga</h2>
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-lg-0">
                            <div class="training-card">
                                <h3 class="title-h3 mb-2 text-black">Cumulative Hours</h3>
                                <h4 class="title-h4">24 Hours</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="training-card">
                                <h3 class="title-h3 mb-2 text-black">Yoga Instructor</h3>
                                <h4 class="title-h4 text-uppercase">Javier Pacheco</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="training-inner position-relative mt-3">
                    <span class="hexagon">
                        <img src="<?= get_image_url('workout-2026/hexagon-pink.svg') ?>" class="img-fluid">
                    </span>
                    <h2 class="title-h2 text-capitalize mb-4">Personal Training</h2>
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-lg-0">
                            <div class="training-card gradient-pink">
                                <h3 class="title-h3 mb-2 text-black">Cumulative Hours</h3>
                                <h4 class="title-h4 text-pink">24 Hours</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="training-card gradient-pink">
                                <h3 class="title-h3 mb-2 text-black">Yoga Instructor</h3>
                                <h4 class="title-h4 text-uppercase text-pink">Javier Pacheco</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cmw-1320 find-more py-80">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="find-more-inner">
                    <div class="find-more-content">
                        <h2 class="title-h2 mb-4">Personal Training & Private Yoga</h2>
                        <p class="find-more-desc">Ready to level up? Book your Personal Training Private Yoga session
                            for a
                            personalised boost!
                        </p>
                        <a href="javascript:void(0);" class="find-more-btn">Find out more</a>
                    </div>
                    <div class="find-more-img">
                        <span class="hexagon visible-5">
                            <img src="<?= get_image_url('workout-2026/hexagon-pink.svg') ?>" class="img-fluid">
                        </span>
                        <img src="<?= get_image_url('workout-2026/personal-training.webp') ?>" alt="Find out more"
                            class="img-fluid">
                    </div>
                    <span class="hexagon btm-center visible-5">
                        <img src="<?= get_image_url('workout-2026/hexagon-pink.svg') ?>" class="img-fluid">
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cmw-1320 find-more py-80">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="find-more-inner bg-teal-16">
                    <div class="find-more-img">
                        <span class="hexagon visible-7">
                            <img src="<?= get_image_url('workout-2026/hexagon-teal.svg') ?>" class="img-fluid">
                        </span>
                        <img src="<?= get_image_url('workout-2026/workshops-retreats.webp') ?>" alt="Find out more"
                            class="img-fluid">
                    </div>
                    <div class="find-more-content">
                        <h2 class="title-h2 mb-4">Workshops & Retreats</h2>
                        <p class="find-more-desc">Missed out this year? Catch more upcoming workshops and retreats, be
                            part of a transformative experience!
                        </p>
                        <a href="javascript:void(0);" class="find-more-btn btn-teal">Find out more</a>
                    </div>
                    <span class="hexagon btm-right visible-5">
                        <img src="<?= get_image_url('workout-2026/hexagon-teal.svg') ?>" class="img-fluid">
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cmw-1320 find-more py-80">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="find-more-inner bg-purple-16">
                    <div class="find-more-content">
                        <h2 class="title-h2 mb-4">Reformer Pilates</h2>
                        <p class="find-more-desc">Not given Reformer Pilates a go this year? Discover PURE's
                            Reformer Pilates classes—find your core command and transform
                            your movement!
                        </p>
                        <a href="javascript:void(0);" class="find-more-btn btn-purple">Find out more</a>
                    </div>
                    <div class="find-more-img">
                        <span class="hexagon visible-5">
                            <img src="<?= get_image_url('workout-2026/hexagon-purple.svg') ?>" class="img-fluid">
                        </span>
                        <img src="<?= get_image_url('workout-2026/reformer-pilates.webp') ?>" alt="Find out more"
                            class="img-fluid">
                    </div>
                    <span class="hexagon btm-center visible-5">
                        <img src="<?= get_image_url('workout-2026/hexagon-purple.svg') ?>" class="img-fluid">
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cmw-1320 find-more py-80">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="find-more-inner bg-grey-40">
                    <div class="find-more-img">
                        <span class="hexagon">
                            <img src="<?= get_image_url('workout-2026/hexagon-grey.svg') ?>" class="img-fluid">
                        </span>
                        <img src="<?= get_image_url('workout-2026/reset.webp') ?>" alt="Find out more"
                            class="img-fluid">
                    </div>
                    <div class="find-more-content">
                        <h2 class="title-h2 mb-4">Re:set</h2>
                        <p class="find-more-desc">Make smart wellness next year's priority. Re:set to a Calmer.
                            Stronger. Better. YOU.
                        </p>
                        <a href="javascript:void(0);" class="find-more-btn btn-grey">Find out more</a>
                    </div>
                    <span class="hexagon btm-right">
                        <img src="<?= get_image_url('workout-2026/hexagon-grey.svg') ?>" class="img-fluid">
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const header = document.querySelector(".header.header--member");

    if (!header) return;

    window.addEventListener("scroll", function() {
        if (window.scrollY > 50) {
            header.classList.add("header-sticky");
        } else {
            header.classList.remove("header-sticky");
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const section = document.querySelector(".active-hours");
    const fills = document.querySelectorAll(".pill-fill");

    if (!section || !fills.length) return;

    const observer = new IntersectionObserver(
        (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {

                    fills.forEach(fill => {
                        const target = fill.style.getPropertyValue("--fill");
                        fill.style.height = target;
                    });

                    observer.disconnect(); //
                }
            });
        }, {
            threshold: 0.35
        }
    );

    observer.observe(section);
});
</script>
<?php
}