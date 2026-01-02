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
            <div class="col-lg-7 text-lg-start text-center mb-5 mb-lg-0">
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
            <div class="col-lg-5 position-relative text-center">
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

<section class="cmw-1320 active-hours py-80">
    <span class="hexagon hex-teal">
        <img src="<?= get_image_url('workout-2026/hexagon-teal.svg') ?>" class="img-fluid">
    </span>
    <span class="hexagon hex-pink">
        <img src="<?= get_image_url('workout-2026/hexagon-pink.svg') ?>" class="img-fluid">
    </span>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="title-h2">Your Active Hours</h2>
            </div>
        </div>
        <div class="row">
            <div class="pill-stats text-center">
                <div class="pill-item">
                    <div class="pill-wrap">
                        <div class="pill-fill" style="--fill:16%;">
                            <span class="wave">
                                <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                                    <path
                                        d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"
                                        fill="#19e6dd"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <p class="title-h3 mt-3 mb-2">Morning (6–9am)</p>
                    <p class="title-h4">16%</p>
                </div>

                <div class="pill-item">
                    <div class="pill-wrap">
                        <div class="pill-fill full" style="--fill:100%;">
                            <span class="wave">
                                <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                                    <path
                                        d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"
                                        fill="#19e6dd"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <p class="title-h3 mt-3 mb-2">Daytime (9am–5pm)</p>
                    <p class="title-h4">125%</p>
                </div>

                <div class="pill-item">
                    <div class="pill-wrap">
                        <div class="pill-fill" style="--fill:24%;">
                            <span class="wave">
                                <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                                    <path
                                        d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"
                                        fill="#19e6dd"></path>
                                </svg>
                            </span>
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
            <div class="col-12 text-center mb-5">
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

                    <div class="glass-card mb-0">
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

<section class="top-teachers py-80 pb-0">
    <span class="hexagon top-center">
        <img src="<?= get_image_url('workout-2026/hexagon-teal.svg') ?>" class="img-fluid">
    </span>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="title-h2">Top 3 Yoga Teachers</h2>
            </div>
        </div>

        <div class="row align-items-end">
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
            <div class="col-12 text-center mb-5">
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

                    <div class="glass-card mb-0">
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
<section class="top-teachers py-80 pb-0">
    <span class="hexagon top-center">
        <img src="<?= get_image_url('workout-2026/hexagon-pink.svg') ?>" class="img-fluid">
    </span>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="title-h2">Your Top 3 Group Fitness (Gx) Instructors</h2>
            </div>
        </div>

        <div class="row align-items-end">
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
            <div class="col-12 text-center mb-5">
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

                    <div class="glass-card mb-0">
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
<section class="top-teachers py-80 pb-0">
    <span class="hexagon top-center">
        <img src="<?= get_image_url('workout-2026/hexagon-purple.svg') ?>" class="img-fluid">
    </span>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="title-h2">Top 3 Reformer Pilates Teachers</h2>
            </div>
        </div>

        <div class="row align-items-end">
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
                        <div class="col-md-6">
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
                        <div class="col-md-6">
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

<section class="workout-banner">
    <svg class="svg">
        <clipPath id="my-clip-path" clipPathUnits="objectBoundingBox">
            <path
                d="M1,0.728 C1,0.739,0.985,0.762,0.974,0.768 L0.527,0.996 A0.078,0.07,0,0,1,0.473,0.996 L0.026,0.768 C0.015,0.762,0,0.739,0,0.728 V0.272 C0,0.261,0.015,0.238,0.026,0.232 L0.473,0.004 A0.063,0.056,0,0,1,0.5,0 A0.064,0.058,0,0,1,0.527,0.004 L0.974,0.232 C0.985,0.238,1,0.261,1,0.272">
            </path>
        </clipPath>
    </svg>
    <div class="container custmpd">
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty($title)) : ?>
                <h1 class="h1-title text-uppercase"><?php echo $title; ?></h1>
                <?php endif; ?>
                <?php if (!empty($sdescription)) : ?>
                <div class="text-leadin">
                    <p><?php echo $sdescription; ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
        $number_of_friends_you_referred = ($results[0][$table_column_name_prefix.'_03_friends_referr_total_friends_referred_to_pure']) ?: 0;
        $days_with_pure = ($results[0][$table_column_name_prefix.'_02_days_with_pure_pure_1st_start_date_2025_11_04']) ?: 0;
        $workout_percentage_in_top = ($results2[0]['avg_workout_days']) ?: 0;
        $journey = get_field("your_journey", $page_id);
        $jtitle = $journey['title'];
        // $title = ($jtitle[$language]) ? $jtitle[$language] : $jtitle['en'];
        $title = ($current_language == 'en') ? 'Your Journey with PURE' : 'PURE 的探索之旅';
        $days_label = $journey['no_of_days_label'];
        // $days_title = ($days_label[$language]) ? $days_label[$language] : $days_label['en'];
        $days_title = ($current_language == 'en')? 'Total Days with PURE' : '累積投入PURE的精彩日子';
        $cumulative_title = $journey['cumulative_days_label'];
        // $cumu_title = ($cumulative_title[$language]) ? $cumulative_title[$language] : $cumulative_title['en'];
        $cumu_title = ($current_language == 'en') ? 'Total Friends Referred to PURE' : '累積推薦給PURE的朋友';
        $workout_days_2025 = ($results[0][$table_column_name_prefix.'_04_total_workout__total_attended_sessions_in_2025']) ?: 0;
        $time_title = $journey['total_time_label'];
        $timel_title = ($time_title[$language]) ? $time_title[$language] : $time_title['en'];
        $timel_title = ($current_language == 'en') ? 'Total Active Days in 2025' : 'Total PURE days in 2025';

        if ($workout_percentage_in_top >= 51) {

            if ($current_language == 'en') {
                $total_workout_day_msg  = "Congrats on being the Top {$workout_percentage_in_top}% of PURE Cardholders! <br>";
                $total_workout_day_msg .= "<strong>Inspiration:</strong> Phenomenal performance! Your grit sets you apart—keep shining in 2026!";
            } else {
                $total_workout_day_msg  = "您已經躋身PURE持卡人的前{$workout_percentage_in_top}%！ <br>";
                $total_workout_day_msg .= "<strong>靈感：</strong>您的堅持令人佩服，繼續發光發熱，2026再創高峰！";
            }

        } elseif ($workout_percentage_in_top == 50) {

            if ($current_language == 'en') {
                $total_workout_day_msg  = "You're right on par with {$workout_percentage_in_top}% of PURE Cardholders! <br>";
                $total_workout_day_msg .= "<strong>Inspiration:</strong> Steady progress is powerful. Keen to push further? Eyes on the prize and level up next year!";
            } else {
                $total_workout_day_msg  = "您與PURE持卡人的{$workout_percentage_in_top}%表現相當！ <br>";
                $total_workout_day_msg .= "<strong>靈感：</strong>穩步向前就是力量，繼續努力，明年再創佳績！";
            }

        } else {

            if ($current_language == 'en') {
                $total_workout_day_msg  = "You're below {$workout_percentage_in_top}% of PURE Cardholders. <br>";
                $total_workout_day_msg .= "<strong>Inspiration:</strong> Every step counts. Every journey is unique — forge your own path. We're your biggest cheerleader no matter where you want to go!";
            } else {
                $total_workout_day_msg  = "您暫時低於PURE持卡人的{$workout_percentage_in_top}%！ <br>";
                $total_workout_day_msg .= "<strong>靈感：</strong>每一步都是進步，堅持屬於您的節奏，2026一起加油！";
            }
        }
        if ($current_language == 'en') {
            $total_workout_day_msg = "PURE Cardholders averaged {$workout_percentage_in_top} PURE days in 2025.";
        }else{
            $total_workout_day_msg = "PURE 持卡人於 2025 年的平均活躍日數為{$workout_percentage_in_top} 。";
        }
        ?>
    <div class="journey-wrap">
        <div class="container custmpd">
            <div class="row">
                <?php if (!empty($title)) : ?>
                <div class="col-md-12">
                    <h2 class="h2-title text-uppercase"><?php echo $title; ?></h2>
                </div>
                <?php endif; ?>
                <div class="col-md-4 text-center">
                    <div class="d-flex flex-column align-items-center h-100">
                        <?php if (!empty($days_title)) : ?>
                        <h3 class="h3-title"><?php echo $days_title; ?></h3>
                        <?php endif; ?>
                        <?php if (!empty($days_with_pure)) : ?>
                        <h4 class="h4-title"><?php echo $days_with_pure; ?></h4>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4 text-center seline">
                    <div class="d-flex flex-column align-items-center h-100">
                        <?php if (!empty($cumu_title)) : ?>
                        <h3 class="h3-title"><?php echo $cumu_title; ?></h3>
                        <?php endif; ?>
                        <h4 class="h4-title"><?php echo $number_of_friends_you_referred; ?></h4>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="d-flex flex-column justify-content-between align-items-center h-100">
                        <?php
                            if (!empty($timel_title)) : ?>
                        <h3 class="h3-title"><?php echo $timel_title; ?></h3>
                        <?php endif;
                        if( !empty($workout_days_2025) ){?>
                        <h4 class="h4-title"><?php echo $workout_days_2025;?></h4>
                        <p class="p-text-17"><?php echo $total_workout_day_msg; ?></p>
                        <?php }
                            ?>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <?php
                    // Titles
                    $first_class_title = ($current_language == 'en')
                        ? 'First Class of 2025'
                        : '2025 年首堂課';

                    $first_location_title = ($current_language == 'en')
                        ? 'First Location Visited in 2025'
                        : '2025 年首個探索的中心';

                    $longest_workout_streak_title = ($current_language == 'en')
                    ? 'Longest PURE Streak'
                    : '最長的運動連續天數';


                    // Data - Choosing language based value in single variable
                    $class_you_attended_first_time = ($current_language == 'en')
                        ? ($results[0][$table_column_name_prefix.'_05_first_class_of_first_class_in_pure_2025'] ?? $no_data_available_msg)
                        : ($results[0][$table_column_name_prefix.'_05_first_class_of_______pure________________2025'] ?? $no_data_available_msg);

                    $location_you_visited_first_time = ($current_language == 'en')
                        ? ($results[0][$table_column_name_prefix.'_06_first_location_first_location_visited_2025_en'] ?? $no_data_available_msg)
                        : ($results[0][$table_column_name_prefix.'_06_first_location_first_location_visited_2025_cn'] ?? $no_data_available_msg);

                    $longest_workout_streak = ($results[0][$table_column_name_prefix.'_07_longest_consec_longest_streak']) ?: 0;
                    // Title based on language
                    $longest_streak_title = ($current_language == 'en')
                        ? "Longest Workout Streak [{$longest_workout_streak} Days]"
                        : "最長訓練連續天數 [{$longest_workout_streak} 天]";

                    // Message logic based on streak days + language
                    if ($current_language == 'en') {
                        if ($longest_workout_streak >= 30) {
                            $longest_streak_message = "Rock star! You inspire commitment!";
                        } elseif ($longest_workout_streak >= 14) {
                            $longest_streak_message = "Fantastic consistency—steady, solid, stand-out dedication!";
                        } elseif ($longest_workout_streak >= 7) {
                            $longest_streak_message = "A strong start—keep building your streak!";
                        } else {
                            $longest_streak_message = "Keep moving—you’re on your way!";
                        }

                    } else { // Chinese
                        if ($longest_workout_streak >= 30) {
                            $longest_streak_message = "您的堅持令人敬佩—超凡毅力！";
                        } elseif ($longest_workout_streak >= 14) {
                            $longest_streak_message = "良好的習慣帶來卓越成果—努力值得肯定！";
                        } elseif ($longest_workout_streak >= 7) {
                            $longest_streak_message = "您已經有了很好的開始—請繼續累積紀錄！";
                        } else {
                            $longest_streak_message = "繼續加油，您正在邁向更好的自己！";
                        }
                    }
                ?>
                <div class="col-md-4 text-center">
                    <div class="d-flex flex-column align-items-center h-100">
                        <h3 class="h3-title"><?php echo $first_class_title; ?></h3>
                        <h4 class="h4-title"><?php echo $class_you_attended_first_time; ?> </h4>
                    </div>
                </div>

                <div class="col-md-4 text-center seline">
                    <div class="d-flex flex-column align-items-center h-100 ">
                        <h3 class="h3-title"><?php echo $first_location_title; ?></h3>
                        <h4 class="h4-title"><?php echo $location_you_visited_first_time; ?></h4>
                    </div>
                </div>

                <div class="col-md-4 text-center ">
                    <div class="d-flex flex-column align-items-center h-100">
                        <h3 class="h3-title"><?php echo $longest_workout_streak_title; ?></h3>
                        <h4 class="h4-title"><?php echo $longest_workout_streak; ?></h4>
                        <p class="p-text-17"><?php echo $longest_streak_message; ?></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="workout-wrap">
    <?php
        $morning_workout_6_to_9am = ($results[0][$table_column_name_prefix.'_08_morning_workou_percentage_between_6am_and_9am']) ?: $no_msg;

        $workout_9am_to_5pm = ($results[0][$table_column_name_prefix.'_09_workouts_9am_5_percentage_between_9am_and_5pm']) ?: $no_msg;

        $workout_after_5pm = ($results[0][$table_column_name_prefix.'_10_workouts_after_percentage_after_5pm']) ?: $no_msg;

        $when_you_workout_label = ($current_language == 'en') ? 'YOUR ACTIVE HOURS' : ' 時段分佈';

        $morning_label = ($current_language == 'en') ? 'Morning (6–9am)': '晨間時段（6–9am）';
        $daytime_label = ($current_language == 'en') ? 'Daytime (9am–5pm)': '日間時段（9am–5pm）';
        $evening_label = ($current_language == 'en') ? 'Evening / Night (after 5pm)': '晚間時段（5pm後）';
    ?>

    <div class="container custmpd my-70 mt-0">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h2 class="h2-title tx-center"><?php echo $when_you_workout_label; ?></h2>
            </div>
            <div class="col-md-4 text-center mt-4 mt-md-0">
                <h3 class="h3-title"><?php echo $morning_label; ?></h3>
                <h4 class="h4-title">
                    <?php  echo is_numeric($morning_workout_6_to_9am) ? $morning_workout_6_to_9am.'%' : $morning_workout_6_to_9am; ?>
                </h4>
            </div>
            <div class="col-md-4 text-center mt-4 mt-md-0">
                <h3 class="h3-title"><?php echo $daytime_label; ?></h3>
                <h4 class="h4-title">
                    <?php  echo is_numeric($workout_9am_to_5pm) ? $workout_9am_to_5pm.'%' : $workout_9am_to_5pm; ?></h4>
            </div>
            <div class="col-md-4 text-center mt-4 mt-md-0">
                <h3 class="h3-title"><?php echo $evening_label; ?></h3>
                <h4 class="h4-title">
                    <?php  echo is_numeric($workout_after_5pm) ? $workout_after_5pm.'%' : $workout_after_5pm; ?></h4>
            </div>
        </div>
    </div>
</section>

<?php
    $most_active_month_label = ($current_language == 'en') ? 'Your most active month' : '您最積極的月份';

    $raw_month = $results[0][$table_column_name_prefix.'_12_month_with_mos_month_with_most_check_in'] ?? null;
    $total_days_check_in = $results3[0]['COL5'] ?? null;

    // Convert to timestamp
    $timestamp = strtotime($raw_month . "-01");

    // Full month name (February)
    $full_month = date("F", $timestamp);

    $months_cn = [
        'January' => '一月','February' => '二月','March' => '三月','April' => '四月',
        'May' => '五月','June' => '六月','July' => '七月','August' => '八月',
        'September' => '九月','October' => '十月','November' => '十一月','December' => '十二月'
    ];

    $month_output = ($current_language == 'en')
        ? $full_month
        : $months_cn[$full_month];
?>

<section class="mst-activemonth my-70">
    <div class="container custmpd">
        <div class="row">
            <div class="col-md-9 text-center mx-auto">
                <div class="activemonth-content">
                    <h2 class="h2-title mb-2"><?php echo $most_active_month_label; ?></h2>
                    <h4 class="h4-title"><?php echo $month_output;?></h4>
                    <?php
                        if($current_language == 'en'){?>
                    <p class="p-text-17 mt-0 yoga-clr"><?php echo $total_days_check_in.' Active days'; ?></p>
                    <?php }else{?>
                    <p class="p-text-17 mt-0 yoga-clr"><?php echo '本月活躍日數：'. $total_days_check_in .'日'; ?></p>
                    <?php }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    $yoga_class_label = ($current_language == 'en') ? 'Yoga Classes' : '瑜伽課堂';
    $class_attended_label = ($current_language == 'en') ? 'Classes Attended' : '參與的課堂';
    $total_attended_yoga_classes = ($results[0][$table_column_name_prefix.'_13_yoga_classes_a_2025']) ?: 0;

    $your_percent = ($results[0][$table_column_name_prefix.'_13_yoga_classes_a_outperformed']) ?: 0; // example: 67

    $average_activity_percentage = 50;
    $compare_percent = (int) $average_activity_percentage; // example: 50

    // Determine label
    $is_above = $your_percent >= $compare_percent;

    // Message for comparison
    $comparison_text_en = $is_above
        ? "More active than {$your_percent}% of PURE yogis!"
        : "Less active than {$your_percent}% of PURE yogis.";

    $comparison_text_cn = $is_above
        ? "比 {$your_percent}% 的 PURE 瑜伽者更活躍！"
        : "比 {$your_percent}% 的 PURE 瑜伽者活躍度較低。";

    // Inspirational quote
    $inspiration_text_en = $is_above
        ? "Omm-azing! Your devotion shines through your action!"
        : "Pose after pose, you're progressing—keep exploring your practice next year!";

    $inspiration_text_cn = $is_above
        ? "太棒了！你的投入讓你閃閃發光！"
        : "一步一式，你正在進步—明年繼續深入探索！";

    // Final dynamic output based on language
    $comparison_text = ($current_language == 'en') ? $comparison_text_en : $comparison_text_cn;
    $inspiration_text = ($current_language == 'en') ? $inspiration_text_en : $inspiration_text_cn;

?>

<section class="yogaclass-detail yoga-wrap pt-1">
    <div class="container custmpd">
        <div class="row">
            <div class="col-md-12">
                <h2 class="h2-title text-uppercase yoga-clr tx-center"><?php echo $yoga_class_label; ?>
                </h2>
            </div>
            <div class="col-md-4 text-center yogadetail">
                <div class="d-flex flex-column align-items-center h-100">
                    <h3 class="h3-title"><?php echo $class_attended_label; ?></h3>
                    <h4 class="h4-title"><?php echo $total_attended_yoga_classes; ?></h4>
                </div>
            </div>
            <div class="col-md-8 text-center seline br-none yogadetail">
                <div class="d-flex flex-column">
                    <h4 class="h5-title"><?php echo $inspiration_text; ?></h4>
                </div>
            </div>
            <!-- <div class="col-md-12 text-left findmore-btn">
                <a href="javascript:void(0);" class="text-uppercase">Find out more</a>
            </div> -->
        </div>
    </div>

    <?php
        $year_on_year_yoga_comparison_label = ($current_language == 'en') ? 'Year-on-year Yoga Comparison' : '瑜珈課程年度比較';
        $yoga_percentage_2024 = ($results[0][$table_column_name_prefix.'_14_yoga_classes_2_number_of_yoga_classes_attended_in_2024']) ?: 0;
        $yoga_percentage_2025 = ($results[0][$table_column_name_prefix.'_14_yoga_classes_2_number_of_yoga_classes_attended_in_2025']) ?: 0;
    ?>

    <div class="container custmpd my-70">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h2 class="h2-title tx-center"><?php echo $year_on_year_yoga_comparison_label; ?></h2>
            </div>
            <div class="col-6 col-md-4 text-center">
                <h3 class="h3-title">2024</h3>
                <h4 class="h4-title"><?php echo $yoga_percentage_2024; ?></h4>
            </div>
            <div class="col-6 col-md-4 text-center">
                <h3 class="h3-title">2025</h3>
                <h4 class="h4-title">
                    <?php echo $yoga_percentage_2025; ?>
                    <svg class="<?php echo ( $yoga_percentage_2024 > $yoga_percentage_2025 ) ? 'down-arrow': 'up-arrow'?> <?php echo  ( trim( $yoga_percentage_2024 ) == trim( $yoga_percentage_2025 ) ) ? ' d-none': ''; ?>"
                        xmlns="http://www.w3.org/2000/svg" width="30" height="36" viewBox="0 0 31 37" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M16.1577 37L30.4374 18.5044C30.9436 17.8487 30.8225 16.9067 30.1667 16.4004C29.511 15.8942 28.569 16.0153 28.0627 16.6711L16.0924 32.1755L3.41212 16.6393C2.8883 15.9975 1.94338 15.9018 1.30159 16.4257C0.659789 16.9495 0.564149 17.8944 1.08797 18.5362L16.1577 37Z"
                            fill="#14E1DC"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M16.1577 21L30.4374 2.50442C30.9436 1.84869 30.8225 0.9067 30.1667 0.400436C29.511 -0.105829 28.569 0.0153389 28.0627 0.671074L16.0924 16.1755L3.41212 0.639286C2.8883 -0.00251198 1.94338 -0.0981503 1.30159 0.425671C0.659789 0.94949 0.564149 1.89441 1.08797 2.53621L16.1577 21Z"
                            fill="#14E1DC"></path>
                    </svg>
                </h4>
            </div>
            <div class="col-md-4 text-center">
                <?php
                    if($current_language == 'en'){?>
                <p class="review-text mt-4 mt-md-0">“Yoga is a journey of the self, <br>
                    through the self, to the self.”</p>
                <?php }else{?>
                <p class="review-text mt-4 mt-md-0">“瑜伽是通往自我的旅程。”</p>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php
        $top_3_yoga_classes_label = ($current_language == 'en') ? 'Top 3 Yoga Classes ' : '前三名的瑜珈課程';

        // 3 class title pairs from your API result
        $classes_raw = [
            [
                'en' => $results[0][$table_column_name_prefix.'_15_top3_yoga_clas_class_name_top_1_english'] ?? '',
                'cn' => $results[0][$table_column_name_prefix.'_15_top3_yoga_clas_class_name_top_1_chinese'] ?? '',
            ],
            [
                'en' => $results[0][$table_column_name_prefix.'_15_top3_yoga_clas_class_name_top_2_english'] ?? '',
                'cn' => $results[0][$table_column_name_prefix.'_15_top3_yoga_clas_class_name_top_2_chinese'] ?? '',
            ],
            [
                'en' => $results[0][$table_column_name_prefix.'_15_top3_yoga_clas_class_name_top_3_english'] ?? '',
                'cn' => $results[0][$table_column_name_prefix.'_15_top3_yoga_clas_class_name_top_3_chinese'] ?? '',
            ],
        ];

        $yoga_classes = [];

        foreach ($classes_raw as $c) {

            // Find class by exact EN title
            $post = get_class_by_title( $c['en'], 'class-type' );

            if ( $post && has_term( 'yoga', 'post_tag', $post->ID ) ) {

                $yoga_classes[] = [
                    'name' => ($current_language == 'en')
                                ? ($c['en'] ?: $no_data_available_msg)
                                : ($c['cn'] ?: $no_data_available_msg),

                    'desc' => get_field_i18n('description', $post->ID) ?: $no_data_available_msg
                ];
            }
        }
    if(!empty($yoga_classes)) { ?>
    <div class="container custmpd modal-wrap">
        <div class="row">
            <div class="col-md-12">
                <h2 class="h2-title tx-center"><?php echo $top_3_yoga_classes_label; ?></h2>
            </div>
            <?php foreach($yoga_classes as $i=>$cl):
                $modal_id="yoga_class_modal_".($i+1);
            ?>
            <div class="col-md-4 text-center <?php echo($i==1?'seline':'')?>">
                <div class="icon-relative">
                    <h3 class="h4-title cursor-pointer" data-mdb-toggle="modal"
                        data-mdb-target="#<?php echo $modal_id?>">
                        <?php echo $cl['name']?>
                    </h3>
                    <p class="class-desc"><?php echo $cl['desc']?></p>

                    <!-- Modal -->
                    <div class="modal fade" id="<?php echo $modal_id?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                <p class="h2 h2p mb-2"><?php echo $cl['name']?></p>
                                <p class="moredesc"><?php echo $cl['desc']?></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    }
        $top_3_yoga_teachers_label = ($current_language == 'en') ? 'Top 3 Yoga Teachers' : '前三名的瑜珈老師';

        $teachers_raw = [
            $results[0][$table_column_name_prefix.'_16_top3_yoga_inst_teacher_name_top_1_english'] ?? '',
            $results[0][$table_column_name_prefix.'_16_top3_yoga_inst_teacher_name_top_2_english'] ?? '',
            $results[0][$table_column_name_prefix.'_16_top3_yoga_inst_teacher_name_top_3_english'] ?? '',
        ];

        $yoga_teachers = [];

        foreach ($teachers_raw as $name) {
            if (!$name) continue; // skip empty
            // 1) Fast exact lookup by title
            $post = get_class_by_title($name, 'teacher');

            if ($post) {
                // 2) Check if yoga teacher via meta
                $is_yoga = get_post_meta($post->ID, 'is_yoga', true);
                if ($is_yoga == '1') {

                    $yoga_teachers[] = [
                        'name' => get_field_i18n('name', $post->ID) ?: $name,
                        'img'  => get_field('hexagon_image', $post->ID) ?: $default_teacher_img,
                    ];
                }
            }
        }
    ?>

    <?php if(!empty($yoga_teachers)) : ?>
    <div class="ycwrap">
        <div class="container custmpd top-gurus">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="h2-title tx-center"><?php echo $top_3_yoga_teachers_label; ?></h2>
                </div>
                <div class="gurus-wrap d-flex flex-wrap">
                    <?php foreach($yoga_teachers as $t): ?>
                    <div class="innerwrap">
                        <div class="gurus-member">
                            <!-- SVG Shape -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="244" height="275" viewBox="0 0 244 275"
                                fill="none">
                                <path
                                    d="M9.12156 61.9349L112.986 3.0412C118.488 -0.0782071 125.223 -0.0816613 130.727 3.03211L234.862 61.9381C240.509 65.1322 244 71.1178 244 77.6052V196.881C244 203.345 240.534 209.313 234.919 212.516L130.784 271.919C125.25 275.076 118.46 275.072 112.93 271.91L9.06504 212.519C3.4592 209.314 0 203.351 0 196.893V77.5929C0 71.112 3.48395 65.1315 9.12156 61.9349Z"
                                    fill="url(#paint0_linear_77_777)"></path>
                                <defs>
                                    <linearGradient id="paint0_linear_77_777" x1="1.86383e-06" y1="212.513" x2="244"
                                        y2="75.013" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#14e1dc"></stop>
                                        <stop offset="1"></stop>
                                    </linearGradient>
                                </defs>
                            </svg>

                            <!-- Avatar -->
                            <div class="gurus-avatar">
                                <svg class="svg">
                                    <clipPath id="clip">
                                        <path
                                            d="M1,0.728C1,0.739,0.985,0.762,0.974,0.768L0.527,0.996A0.078,0.07,0,0,1,0.473,0.996L0.026,0.768C0.015,0.762,0,0.739,0,0.728V0.272C0,0.261,0.015,0.238,0.026,0.232L0.473,0.004A0.063,0.056,0,0,1,0.5,0A0.064,0.058,0,0,1,0.527,0.004L0.974,0.232C0.985,0.238,1,0.261,1,0.272" />
                                    </clipPath>
                                </svg>

                                <img src="<?php echo esc_url($t['img']) ?>" class="memberimg" width="244"
                                    height="275" />
                            </div>
                        </div>
                        <p class="h3-title mb-0 text-center"><?php echo $t['name'] ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div style="height:60px;width:100%;"></div>
    <?php endif; ?>
</section>

<?php if($total_attended_yoga_classes == 0){ ?>
<section class="noyogayear my-70 mt-0">
    <div class="container custmpd">
        <div class="row">
            <div class="col-12 text-center mx-auto">
                <div class="noyogayear-content bg-lightbx">
                    <?php
                    if($current_language == 'en'){?>
                    <h3 class="h3-title-700">No yoga this year? <br>
                        Explore PURE's diverse yoga classes—unroll your mat and start 'posing'!</h3>
                    <div class="text-center findmore-btn mt-4">
                        <a href="<?php echo home_url('yoga')?>" class="text-uppercase">Find out more</a>
                    </div>
                    <?php }else{?>
                    <h3 class="h3-title-700">今年還沒有體驗瑜伽嗎？ <br>探索 PURE 多元瑜伽課堂，展開您的專屬旅程！</h3>
                    <div class="text-center findmore-btn mt-4">
                        <a href="<?php echo home_url('yoga')?>" class="text-uppercase">了解更多</a>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php }
$group_fitness_section_heading = ($current_language == 'en') ? 'Group Fitness (GX) Classes' : '團體健身課堂 (GX)';

$class_attended_label = ($current_language == 'en') ? 'Classes Attended' : '參與的課堂';
$total_attended_gx_classes = ($results[0][$table_column_name_prefix.'_17_gx_classes_att_2025']) ?: 0;

$your_percent = ($results[0][$table_column_name_prefix.'_17_gx_classes_att_outperformed']) ?: 0; // example: 67

$average_activity_percentage = 50;
$compare_percent = (int) $average_activity_percentage; // example: 50

// Determine label
$is_above = $your_percent >= $compare_percent;

// Message for comparison
$comparison_text_en = $is_above
    ? "More active than {$your_percent}% of PURE Cardholders!"
    : "Less active than {$your_percent}% of PURE Cardholders.";

$comparison_text_cn = $is_above
? "您的活躍度高於 {$your_percent}% 的 PURE 持卡人！"
: "您的活躍度低於 {$your_percent}% 的 PURE 持卡人。";

// Inspirational quote
$inspiration_text_en = $is_above
    ? "Not only setting but raising the bar...awe-inspiring!"
    : "Every class is a win—keep moving and reach new heights next year!";

$inspiration_text_cn = $is_above
    ? "您的活力令人振奮──繼續突破自我！"
    : "每一堂課都是進步──明年一起再多參與吧！";

// Final dynamic output based on language
$comparison_text   = ($current_language == 'en') ? $comparison_text_en   : $comparison_text_cn;
$inspiration_text  = ($current_language == 'en') ? $inspiration_text_en  : $inspiration_text_cn;
?>

<section class="yogaclass-detail fitness-wrap pt-1">
    <div class="container custmpd">
        <div class="row">
            <div class="col-md-12">
                <h2 class="h2-title text-uppercase fit-clr tx-center">
                    <?php echo $group_fitness_section_heading; ?>
                </h2>
            </div>
            <div class="col-md-4 text-center yogadetail">
                <div class="d-flex flex-column align-items-center h-100">
                    <h3 class="h3-title"><?php echo $class_attended_label; ?></h3>
                    <h4 class="h4-title fit-clr"><?php echo $total_attended_gx_classes; ?></p>
                </div>
            </div>
            <div class="col-md-8 text-center seline br-none yogadetail">
                <div class="d-flex flex-column">
                    <h4 class="h5-title fit-clr"><?php echo $inspiration_text; ?></h4>
                </div>
            </div>
        </div>
    </div>

    <?php
        $year_on_year_group_fitness_comparison_label = ($current_language == 'en') ? 'Year-on-year Group Fitness Comparison' : '年度團體健身比較';
        $group_fitness_percentage_2024 = ($results[0][$table_column_name_prefix.'_18_gx_classes_202_number_of_gx_classes_attended_in_2024']) ?: 0;
        $group_fitness_percentage_2025 = ($results[0][$table_column_name_prefix.'_18_gx_classes_202_number_of_gx_classes_attended_in_2025']) ?: 0;
    ?>

    <div class="container custmpd my-70">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h3 class="h2-title tx-center"><?php echo $year_on_year_group_fitness_comparison_label; ?></h3>
            </div>
            <div class="col-6 col-md-4 text-center">
                <h3 class="h3-title">2024</h3>
                <h4 class="h4-title fit-clr"><?php echo $group_fitness_percentage_2024; ?></h4>
            </div>
            <div class="col-6 col-md-4 text-center">
                <h3 class="h3-title">2025</h3>
                <h4 class="h4-title fit-clr">
                    <?php echo $group_fitness_percentage_2025; ?>
                    <svg class="<?php echo ( $group_fitness_percentage_2024 > $group_fitness_percentage_2025 ) ? 'down-arrow': 'up-arrow'?> <?php echo  ( trim( $group_fitness_percentage_2024 ) == trim( $group_fitness_percentage_2025 ) ) ? ' d-none': ''; ?>"
                        xmlns="http://www.w3.org/2000/svg" width="30" height="36" viewBox="0 0 31 37" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M16.1577 37L30.4374 18.5044C30.9436 17.8487 30.8225 16.9067 30.1667 16.4004C29.511 15.8942 28.569 16.0153 28.0627 16.6711L16.0924 32.1755L3.41212 16.6393C2.8883 15.9975 1.94338 15.9018 1.30159 16.4257C0.659789 16.9495 0.564149 17.8944 1.08797 18.5362L16.1577 37Z"
                            fill="#f00f64"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M16.1577 21L30.4374 2.50442C30.9436 1.84869 30.8225 0.9067 30.1667 0.400436C29.511 -0.105829 28.569 0.0153389 28.0627 0.671074L16.0924 16.1755L3.41212 0.639286C2.8883 -0.00251198 1.94338 -0.0981503 1.30159 0.425671C0.659789 0.94949 0.564149 1.89441 1.08797 2.53621L16.1577 21Z"
                            fill="#f00f64"></path>
                    </svg>
                </h4>
            </div>
            <div class="col-md-4 text-center">
                <?php
                    if($current_language == 'en'){?>
                <p class="review-text mt-4 mt-md-0">“Strength grows in those <br>
                    moments when you think you <br> can't go on but you keep <br> going.”
                </p>
                <?php }else{?>
                <p class="review-text mt-4 mt-md-0">“真正的力量，來自堅持下去的那一刻。”</p>
                <?php } ?>

            </div>
        </div>
    </div>

    <?php
        $top_3_fitness_classes_label = ($current_language == 'en') ? 'Top 3 Group Fitness Classes ' : '前三名的團體健身課程';

        // 3 class title pairs from your API result
        $classes_raw = [
            [
                'en' => $results[0][$table_column_name_prefix.'_19_top3_gx_classe_class_name_top_1_english'] ?? '',
                'cn' => $results[0][$table_column_name_prefix.'_19_top3_gx_classe_class_name_top_1_chinese'] ?? '',
            ],
            [
                'en' => $results[0][$table_column_name_prefix.'_19_top3_gx_classe_class_name_top_2_english'] ?? '',
                'cn' => $results[0][$table_column_name_prefix.'_19_top3_gx_classe_class_name_top_2_chinese'] ?? '',
            ],
            [
                'en' => $results[0][$table_column_name_prefix.'_19_top3_gx_classe_class_name_top_3_english'] ?? '',
                'cn' => $results[0][$table_column_name_prefix.'_19_top3_gx_classe_class_name_top_3_chinese'] ?? '',
            ],
        ];

        $fitness_classes = [];

        foreach($classes_raw as $c){
            if (!$c['en']) continue; // skip blank

            // 1. Fast exact match by EN title
            $post = get_class_by_title($c['en'], 'class-type');

            if ($post) {

                // 2. Verify this class has the "fitness" tag
                if (has_term('fitness', 'post_tag', $post->ID)) {

                    $fitness_classes[] = [
                        'name' => ($current_language == 'en')
                                    ? ($c['en'] ?: $no_data_available_msg)
                                    : ($c['cn'] ?: $no_data_available_msg),

                        'desc' => get_field_i18n('description', $post->ID) ?: $no_data_available_msg
                    ];
                }
            }
        }
        if(!empty($fitness_classes)) {
    ?>
    <div class="container custmpd modal-wrap modal-fit-clr">
        <div class="row">
            <div class="col-md-12">
                <h2 class="h2-title tx-center"><?php echo $top_3_fitness_classes_label; ?></h2>
            </div>

            <?php foreach($fitness_classes as $i=>$cl):
                $modal_id="fitness_group_class_modal_".($i+1);
            ?>
            <div class="col-md-4 text-center <?php echo($i==1?'seline':'')?>">
                <div class="icon-relative">
                    <h3 class="h4-title cursor-pointer fit-clr" data-mdb-toggle="modal"
                        data-mdb-target="#<?php echo $modal_id?>">
                        <?php echo $cl['name']?>
                    </h3>
                    <p class="class-desc"><?php echo $cl['desc']?></p>
                    <!-- Modal -->
                    <div class="modal fade" id="<?php echo $modal_id?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                <p class="h2 h2p mb-2"><?php echo $cl['name']?></p>
                                <p class="moredesc"><?php echo $cl['desc']?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php }

    $top_3_fitness_group_instrcuctor_title = ($current_language == 'en') ? 'Your top 3 Group Fitness (GX) Instructors' : '您最推薦的 3 位團體健身 (GX) 教練';

    $fitness_teachers_raw = [
        $results[0][$table_column_name_prefix.'_20_top3_gx_instru_teacher_name_top_1_english'] ?? '',
        $results[0][$table_column_name_prefix.'_20_top3_gx_instru_teacher_name_top_2_english'] ?? '',
        $results[0][$table_column_name_prefix.'_20_top3_gx_instru_teacher_name_top_3_english'] ?? '',
    ];

    $fitness_teachers = [];

    foreach ($fitness_teachers_raw as $name) {

        if (!$name) continue; // skip blank

        // 1. Fast exact title match
        $post = get_class_by_title($name, 'teacher');

        if ($post) {

            // 2. Verify fitness teacher meta
            $is_fitness = get_post_meta($post->ID, 'is_fitness', true);

            if ($is_fitness == '1') {

                $fitness_teachers[] = [
                    'name' => get_field_i18n('name', $post->ID) ?: $name,
                    'img'  => get_field('hexagon_image', $post->ID) ?: $default_teacher_img,
                ];
            }
        }
    }

    if(!empty($fitness_teachers)){ ?>
    <div class="ycwrap">
        <div class="container custmpd top-gurus">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="h2-title tx-center"><?php echo $top_3_fitness_group_instrcuctor_title; ?></h2>
                </div>
                <div class="gurus-wrap d-flex flex-wrap">
                    <?php foreach($fitness_teachers as $t){ ?>
                    <div class="innerwrap">
                        <div class="gurus-member">
                            <svg xmlns="http://www.w3.org/2000/svg" width="244" height="275" viewBox="0 0 244 275"
                                fill="none">
                                <path
                                    d="M9.12156 61.9349L112.986 3.0412C118.488 -0.0782071 125.223 -0.0816613 130.727 3.03211L234.862 61.9381C240.509 65.1322 244 71.1178 244 77.6052V196.881C244 203.345 240.534 209.313 234.919 212.516L130.784 271.919C125.25 275.076 118.46 275.072 112.93 271.91L9.06504 212.519C3.4592 209.314 0 203.351 0 196.893V77.5929C0 71.112 3.48395 65.1315 9.12156 61.9349Z"
                                    fill="url(#paint1)"></path>
                                <defs>
                                    <linearGradient id="paint1" x1="1.86383e-06" y1="212.513" x2="244" y2="75.013"
                                        gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#f00f64"></stop>
                                        <stop offset="1"></stop>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <div class="gurus-avatar">
                                <img src="<?php echo esc_url($t['img']) ?>" class="memberimg" alt="Fitness Gurus Shape"
                                    width="244" height="275">
                            </div>
                        </div>
                        <p class="h3-title mb-0 text-center fit-clr"><?php echo $t['name'] ?></p>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php } if($total_attended_gx_classes == 0 ){?>
    <div class="nogxyear pt-0">
        <div class="container custmpd">
            <div class="row">
                <div class="col-12 text-center mx-auto">
                    <div class="nogxyear-content bg-lightbx">
                        <?php
                        if($current_language == 'en'){?>
                        <h3 class="h3-title-700">Didn't try Group Fitness (GX) this year? <br>
                            Experience PURE's dynamic group classes - move stronger, together!</h3>
                        <div class="text-center findmore-btn mt-4">
                            <a href="<?php echo home_url('fitness')?>" class="text-uppercase bg-fit-clr">Find Out
                                More</a>
                        </div>
                        <?php }else{?>
                        <h3 class="h3-title-700">今年沒有參加團體健身課程（GX）嗎？ <br>
                            體驗 PURE 充滿活力的團體課程——一起動得更強、更有力！
                        </h3>
                        <div class="text-center findmore-btn mt-4">
                            <a href="<?php echo home_url('fitness')?>" class="text-uppercase bg-fit-clr">了解更多</a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</section>
<?php

$reformer_pilates_section_heading = ($current_language == 'en') ? 'Reformer Pilates Classes' : '器械普拉提課堂';

$class_attended_label = ($current_language == 'en') ? 'Classes Attended' : '參與的課堂';
$total_attended_reformer_pilates_classes = ($results[0][$table_column_name_prefix.'_21_reformer_pilat_2025']) ?: 0;

$your_percent = ($results[0][$table_column_name_prefix.'_21_reformer_pilat_outperformed']) ?: 0; // example: 67

$average_activity_percentage = 50;
$compare_percent = (int) $average_activity_percentage; // example: 50

// Determine label
$is_above = $your_percent >= $compare_percent;

// Message for comparison
$comparison_text_en = $is_above
    ? "More active than {$your_percent}% of PURE Cardholders!"
    : "Less active than {$your_percent}% of PURE Cardholders.";

$comparison_text_cn = $is_above
? "您的活躍度高於 {$your_percent}% 的 PURE 持卡人！"
: "您的活躍度低於 {$your_percent}% 的 PURE 持卡人。";

// Inspirational quote
$inspiration_text_en = $is_above
    ? "Your core control and commitment are equally impressive. Keep rising to the challenge!"
    : "Every session builds a stronger you—stay committed and witness your growth next year!";

$inspiration_text_cn = $is_above
    ? "您的力量與控制力不斷提升──繼續挑戰自我！"
    : "每一次練習都讓您變得更強壯──持之以恆，明年見證更大的進步！";

// Final dynamic output based on language
$comparison_text   = ($current_language == 'en') ? $comparison_text_en   : $comparison_text_cn;
$inspiration_text  = ($current_language == 'en') ? $inspiration_text_en  : $inspiration_text_cn;

?>

<section class="yogaclass-detail reformer-wrap pt-1">
    <div class="container custmpd">
        <div class="row">
            <div class="col-md-12">
                <h2 class="h2-title text-uppercase ref-clr tx-center">
                    <?php echo $reformer_pilates_section_heading; ?></h2>
            </div>
            <div class="col-md-4 text-center reformer-pilates-dt yogadetail">
                <div class="d-flex flex-column align-items-center h-100 ">
                    <h3 class="h3-title"><?php echo $class_attended_label; ?></h3>
                    <h4 class="h4-title ref-clr"><?php echo $total_attended_reformer_pilates_classes; ?></h4>
                </div>
            </div>
            <div class="col-md-8 text-center seline br-none yogadetail">
                <div class="d-flex flex-column">
                    <h4 class="h5-title ref-clr"><?php echo $inspiration_text; ?></h4>
                </div>
            </div>

        </div>
    </div>

    <?php
        $year_on_year_reformer_pilates_comparison_label = ($current_language == 'en') ? 'Year-on-year Reformer Pilates comparison' : '普拉提器材年度對比';
        $reformer_pilates_percentage_2024 = ($results[0][$table_column_name_prefix.'_22_reformer_pilat_number_of_reformer_pilate_ed_in_2024']) ?: 0;
        $reformer_pilates_percentage_2025 = ($results[0][$table_column_name_prefix.'_22_reformer_pilat_number_of_reformer_pilate_ed_in_2025']) ?: 0;
    ?>

    <div class="container custmpd my-70">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h2 class="h2-title tx-center"><?php echo $year_on_year_reformer_pilates_comparison_label; ?>
                </h2>
            </div>
            <div class="col-6 col-md-4 text-center">
                <h3 class="h3-title">2024</h3>
                <h4 class="h4-title ref-clr"><?php echo $reformer_pilates_percentage_2024; ?></h4>
            </div>
            <div class="col-6 col-md-4 text-center">
                <h3 class="h3-title">2025</h3>
                <h4 class="h4-title ref-clr">
                    <?php echo $reformer_pilates_percentage_2025; ?>
                    <svg class="<?php echo ( $reformer_pilates_percentage_2024 > $reformer_pilates_percentage_2025 ) ? 'down-arrow': 'up-arrow'?> <?php echo  ( trim( $reformer_pilates_percentage_2024 ) == trim( $reformer_pilates_percentage_2025 ) ) ? ' d-none': ''; ?>"
                        xmlns="http://www.w3.org/2000/svg" width="30" height="36" viewBox="0 0 31 37" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M16.1577 37L30.4374 18.5044C30.9436 17.8487 30.8225 16.9067 30.1667 16.4004C29.511 15.8942 28.569 16.0153 28.0627 16.6711L16.0924 32.1755L3.41212 16.6393C2.8883 15.9975 1.94338 15.9018 1.30159 16.4257C0.659789 16.9495 0.564149 17.8944 1.08797 18.5362L16.1577 37Z"
                            fill="#5E4D9B"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M16.1577 21L30.4374 2.50442C30.9436 1.84869 30.8225 0.9067 30.1667 0.400436C29.511 -0.105829 28.569 0.0153389 28.0627 0.671074L16.0924 16.1755L3.41212 0.639286C2.8883 -0.00251198 1.94338 -0.0981503 1.30159 0.425671C0.659789 0.94949 0.564149 1.89441 1.08797 2.53621L16.1577 21Z"
                            fill="#5E4D9B"></path>
                    </svg>
                </h4>
            </div>
            <div class="col-md-4 text-center">
                <?php
                    if($current_language == 'en'){?>
                <p class="review-text mt-4 mt-md-0">“Control, precision, persistence—the holy trinity of Reformer
                    Pilates.”</p>
                <?php }else{?>
                <p class="review-text mt-4 mt-md-0">“控制、精準與堅持──這就是器械普拉提的力量。”</p>
                <?php }
                ?>
            </div>
        </div>
    </div>

    <?php
        $top_3_reformer_pilates_classes_label = ($current_language == 'en') ? 'Top 3 Reformer Pilates Classes ' : '排名前三的普拉提器材課程';

        // 3 class title pairs from your API result
        $classes_raw = [
            [
                'en' => $results[0][$table_column_name_prefix.'_23_top3_reformer__class_name_top_1_english'] ?? '',
                'cn' => $results[0][$table_column_name_prefix.'_23_top3_reformer__class_name_top_1_chinese'] ?? '',
            ],
            [
                'en' => $results[0][$table_column_name_prefix.'_23_top3_reformer__class_name_top_2_english'] ?? '',
                'cn' => $results[0][$table_column_name_prefix.'_23_top3_reformer__class_name_top_2_chinese'] ?? '',
            ],
            [
                'en' => $results[0][$table_column_name_prefix.'_23_top3_reformer__class_name_top_3_english'] ?? '',
                'cn' => $results[0][$table_column_name_prefix.'_23_top3_reformer__class_name_top_3_chinese'] ?? '',
            ],
        ];

        $reformer_pilates_classes = [];
        foreach ($classes_raw as $c) {

            if (!$c['en']) continue; // skip empty titles

            // 1. Fast exact match by English title
            $post = get_class_by_title($c['en'], 'class-type');

            if ($post) {

                $reformer_pilates_classes[] = [
                    'name' => ($current_language == 'en')
                                ? ($c['en'] ?: $no_data_available_msg)
                                : ($c['cn'] ?: $no_data_available_msg),

                    'desc' => get_field_i18n('description', $post->ID) ?: $no_data_available_msg
                ];
            }
        }
        if(!empty($reformer_pilates_classes)){
    ?>

    <div class="container custmpd modal-wrap modal-ref-clr">
        <div class="row">
            <div class="col-md-12">
                <h2 class="h2-title tx-center"><?php echo $top_3_reformer_pilates_classes_label; ?></h2>
            </div>
            <?php foreach($reformer_pilates_classes as $i=>$cl):
                $modal_id="reformer_group_class_modal_".($i+1);
            ?>
            <div class="col-md-4 text-center <?php echo($i==1?'seline':'')?>">
                <div class="icon-relative">
                    <h4 class="h4-title cursor-pointer ref-clr" data-mdb-toggle="modal"
                        data-mdb-target="#<?php echo $modal_id?>">
                        <?php echo $cl['name']?>
                    </h4>

                    <p class="class-desc"><?php echo $cl['desc']?></p>
                    <!-- Modal -->
                    <div class="modal fade" id="<?php echo $modal_id?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                <p class="h2 h2p mb-2"><?php echo $cl['name']?></p>
                                <p class="moredesc"><?php echo $cl['desc']?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php }?>

    <?php
        $top_3_reformer_pilates_teachers_label = ($current_language == 'en') ? 'Top 3 Reformer Pilates Teachers' : '排名前三的普拉提器材教練';

        $teachers_raw = [
            $results[0][$table_column_name_prefix.'_24_top3_reformer__teacher_name_top_1_english'] ?? '',
            $results[0][$table_column_name_prefix.'_24_top3_reformer__teacher_name_top_2_english'] ?? '',
            $results[0][$table_column_name_prefix.'_24_top3_reformer__teacher_name_top_3_english'] ?? '',
        ];

        $reformer_pilates_teachers = [];

        foreach ($teachers_raw as $name) {
            if (!$name) continue; // skip blank
            // 1. Fast exact title match
            $post = get_class_by_title($name, 'teacher');
            if ($post) {
                $reformer_pilates_teachers[] = [
                    'name' => get_field_i18n('name', $post->ID) ?: $name,
                    'img'  => get_field('hexagon_image', $post->ID) ?: $default_teacher_img,
                ];
            }
        }

        if(!empty($reformer_pilates_teachers)) {
    ?>
    <div class="ycwrap">
        <div class="container custmpd top-gurus">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="h2-title tx-center">
                        <?php echo $top_3_reformer_pilates_teachers_label;?>
                    </h2>
                </div>
                <div class="gurus-wrap d-flex flex-wrap">
                    <?php foreach($reformer_pilates_teachers as $t): ?>
                    <div class="innerwrap">
                        <div class="gurus-member">
                            <!-- SVG Shape -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="244" height="275" viewBox="0 0 244 275"
                                fill="none">
                                <path
                                    d="M9.12156 61.9349L112.986 3.0412C118.488 -0.0782071 125.223 -0.0816613 130.727 3.03211L234.862 61.9381C240.509 65.1322 244 71.1178 244 77.6052V196.881C244 203.345 240.534 209.313 234.919 212.516L130.784 271.919C125.25 275.076 118.46 275.072 112.93 271.91L9.06504 212.519C3.4592 209.314 0 203.351 0 196.893V77.5929C0 71.112 3.48395 65.1315 9.12156 61.9349Z"
                                    fill="url(#shape_10)"></path>
                                <defs>
                                    <linearGradient id="shape_10" x1="1.86383e-06" y1="212.513" x2="244" y2="75.013"
                                        gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#5E4D9B"></stop>
                                        <stop offset="1"></stop>
                                    </linearGradient>
                                </defs>
                            </svg>

                            <!-- Avatar -->
                            <div class="gurus-avatar">
                                <svg class="svg">
                                    <clipPath id="clip">
                                        <path
                                            d="M1,0.728C1,0.739,0.985,0.762,0.974,0.768L0.527,0.996A0.078,0.07,0,0,1,0.473,0.996L0.026,0.768C0.015,0.762,0,0.739,0,0.728V0.272C0,0.261,0.015,0.238,0.026,0.232L0.473,0.004A0.063,0.056,0,0,1,0.5,0A0.064,0.058,0,0,1,0.527,0.004L0.974,0.232C0.985,0.238,1,0.261,1,0.272" />
                                    </clipPath>
                                </svg>

                                <img src="<?php echo esc_url($t['img']) ?>" class="memberimg" width="244"
                                    height="275" />
                            </div>
                        </div>
                        <p class="h3-title mb-0 text-center ref-clr"><?php echo $t['name'] ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php } if($total_attended_reformer_pilates_classes == 0){?>
    <div class="noyogayear my-70 mt-0">
        <div class="container custmpd">
            <div class="row">
                <div class="col-12 text-center mx-auto">
                    <div class="noyogayear-content bg-lightbx">
                        <?php if($current_language == 'en'){?>
                        <h3 class="h3-title-700">Not given Reformer Pilates a go this year? Discover PURE's <br>
                            Reformer Pilates classes—find your core command and transform <br>
                            your
                            movement!</h3>
                        <div class="text-center findmore-btn mt-4">
                            <a href="<?php echo home_url('reformer-pilates/'); ?>"
                                class="text-uppercase bg-ref-clr">Find out more</a>
                        </div>
                        <?php }else{?>
                        <h3 class="h3-title-700">今年還沒體驗器械普拉提嗎? 探索 PURE <br>
                            器械普拉提課堂，強化核心，改變從現在開始！</h3>
                        <div class="text-center findmore-btn mt-4">
                            <a href="<?php echo home_url('reformer-pilates/'); ?>"
                                class="text-uppercase bg-ref-clr">了解更多</a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div> <?php } ?>
</section>
<?php

    $personal_training_label = ($current_language == 'en') ? 'Personal Training': '私人健身訓練';
    $personal_training_inspiration_quote = ($current_language == 'en') ? 'Greatness exists beyond comfort zones': '只有走出舒適圈，才能挑戰自我極限。';
    $cumulative_hours_label = ($current_language == 'en') ? 'Cumulative Hours': '累積時數';
    $cumulative_hours = ($results[0][$table_column_name_prefix.'_25_hours_of_pt_session_duration_one_hour_per_session']) ?: 0;

    $personal_trainer_label = ($current_language == 'en') ? 'Personal Trainer': '私人教練';

    $teachers_raw = [
        $results[0][$table_column_name_prefix.'_25_hours_of_pt_teacher_name_top_1'] ?? '',
        $results[0][$table_column_name_prefix.'_25_hours_of_pt_teacher_name_top_2'] ?? '',
        $results[0][$table_column_name_prefix.'_25_hours_of_pt_teacher_name_top_3'] ?? '',
    ];

    // Remove empty values
    $teachers_filtered = array_filter($teachers_raw);

    // Create comma-separated string
    $teachers_list = implode(', ', $teachers_filtered);

    if(!empty($cumulative_hours)){
?>

<section class="yogaclass-detail gxclass-detail cardholder-pt my-70">
    <div class="container custmpd">
        <div class="row">
            <div class="col-md-12">
                <h2 class="h2-title"><?php echo $personal_training_label; ?></h2>
            </div>
            <div class="col-md-6 text-center yogadetail">
                <div class="d-flex flex-column justify-content-between align-items-center h-100 ">
                    <h3 class="h3-title"><?php echo $cumulative_hours_label; ?></h3>
                    <h4 class="h4-title"><?php echo $cumulative_hours; ?></p>
                </div>
            </div>
            <div class="col-md-6 text-center seline yogadetail">
                <div class="d-flex flex-column justify-content-between align-items-center h-100">
                    <h3 class="h3-title"><?php echo $personal_trainer_label; ?></h3>
                    <h4 class="h4-title"><?php echo $teachers_list; ?></h4>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    }
    $private_yoga_label = ($current_language == 'en') ? 'Private Yoga': '私人瑜伽';
    $private_yoga_inspiration_quote = ($current_language == 'en') ? 'Still your mind to embrace inner strength, self-confidence and robust health.': '內心的平靜帶來力量與自信，這對健康非常重要。';
    $cumulative_py_hours_label = ($current_language == 'en') ? 'Cumulative Hours': '累積時數';
    $cumulative_private_yoga_hours = ($results[0][$table_column_name_prefix.'_26_hours_of_py_session_duration_one_hour_per_session']) ?: 0;

    $personal_trainer_label = ($current_language == 'en') ? 'Yoga Teacher': '瑜伽導師';

    $py_teachers_raw = array();

    $py_teachers_raw = [
        $results[0][$table_column_name_prefix.'_26_hours_of_py_teacher_name_top_1'] ?? '',
        $results[0][$table_column_name_prefix.'_26_hours_of_py_teacher_name_top_2'] ?? '',
        $results[0][$table_column_name_prefix.'_26_hours_of_py_teacher_name_top_3'] ?? '',
    ];

    // Remove empty values
    $py_teachers_filtered = array_filter($py_teachers_raw);

    // Create comma-separated string
    $py_teachers_list = implode(', ', $py_teachers_filtered);

    if(!empty($cumulative_private_yoga_hours)){
?>

<section class="yogaclass-detail gxclass-detail cardholder-py my-70">
    <div class="container custmpd">
        <div class="row">
            <div class="col-md-12">
                <h2 class="h2-title"><?php echo $private_yoga_label; ?></h2>
            </div>
            <div class="col-md-6 text-center yogadetail">
                <div class="d-flex flex-column h-100 ">
                    <h3 class="h3-title"><?php echo $cumulative_py_hours_label; ?></h3>
                    <h4 class="h4-title"><?php echo $cumulative_private_yoga_hours; ?></h4>
                </div>
            </div>
            <div class="col-md-6 text-center seline yogadetail">
                <div class="d-flex flex-column h-100">
                    <h3 class="h3-title"><?php echo $personal_trainer_label; ?></h3>
                    <h4 class="h4-title"><?php echo $py_teachers_list?></h4>
                </div>
            </div>
        </div>
    </div>
</section>
<?php }

if( empty($cumulative_hours) && empty($cumulative_private_yoga_hours) && $table_column_name_prefix != 'sg'){?>
<section class="nogxyear">
    <div class="container custmpd">
        <div class="row">
            <div class="col-12 text-center mx-auto">
                <div class="nogxyear-content bg-lightbx">
                    <?php
                            if($current_language == 'en'){?>
                    <h2 class="h2-title">Personal Training & Private Yoga</h2>
                    <h3 class="h3-title-700">
                        Ready to level up? <br>
                        Book your Personal Training Private Yoga session for a personalised boost!
                    </h3>
                    <div class="text-center findmore-btn mt-4">
                        <a href="<?php echo home_url('fitness/personal-training/')?>" class="">Find out more</a>
                    </div>
                    <?php }else{?>
                    <h2 class="h2-title">私人訓練和私人瑜伽</h2>
                    <h3 class="h3-title-700">準備迎接更好的自己嗎？ <br>
                        立即預約您的 私人健身訓練 私人瑜伽，專屬的改變就在眼前！</h3>
                    <div class="text-center findmore-btn mt-4">
                        <a href="<?php echo home_url('fitness/personal-training/')?>" class="">了解更多</a>
                    </div>
                    <?php }
                        ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php }

$yoga_check = get_field("yoga_check", $page_id);
$lan_title = $yoga_check['title'];
$title = ($lan_title[$language]) ? $lan_title[$language] : $lan_title['en'];
$title = ($current_language == 'en') ? 'Yoga Events & Retreats' : '瑜伽工作坊/靜修營';

$total_yoga_hours = $results[0][$table_column_name_prefix.'_27_yoga_workshops_total_yoga_workshops_and_retreats'] ?? 0;

$yoga_check_banner = $yoga_check['banner_image'];
$ycdesktop_image = get_stylesheet_directory_uri(). '/assets/images/workout/yoga-check-bg.webp';
$ycmobile_image = get_stylesheet_directory_uri(). '/assets/images/workout/yoga-check-bg-m.webp';

if($total_yoga_hours != 0){?>
<section class="yogacheck text-center">
    <?php if (!empty($title)) : ?>
    <h2 class="h2-title text-uppercase mb-1"><?php echo $title; ?></h2>
    <?php
        if($current_language == 'en'){?>
    <h3 class="h3-title mb-0">You joined <?php echo $total_yoga_hours;?> workshops/retreats—deepening your practice and
        self-discovery</h3>
    <?php }else{?>
    <h3 class="h3-title mb-0">參加了<?php echo $total_yoga_hours;?>個瑜伽工作坊/靜修營──深化自我探索旅程。</h3>
    <?php }
    endif; ?>
</section>
<?php }

if($total_yoga_hours == 0){?>

<section class="nogxyear py-0">
    <div class="container custmpd">
        <div class="row">
            <div class="col-12 text-center mx-auto">
                <div class="noyogayear-content bg-lightbx">
                    <?php
                    if($current_language == 'en'){?>
                    <h2 class="h2-title">Workshops & Retreats</h2>
                    <h3 class="h3-title-700">Missed out this year?
                        <br>Catch more upcoming workshops and retreats, be part of a
                        transformative experience!
                    </h3>
                    <div class="text-center findmore-btn mt-4">
                        <a href="<?php echo home_url('happenings/#learninghub')?>" class="bg-fit-clr">Find out more</a>
                    </div>
                    <?php }else{?>
                    <h2 class="h2-title">工作坊和靜修活動</h2>
                    <h3 class="h3-title-700">今年還沒參加？
                        <br>立即探索PURE的工作坊及靜修營，開啟全新體驗！
                    </h3>
                    <div class="text-center findmore-btn mt-4">
                        <a href="<?php echo home_url('happenings/#learninghub')?>" class="bg-fit-clr">了解更多</a>
                    </div>
                    <?php }

                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php }

$reset_by_pure_label = ($current_language == 'en')? 'Re:set by PURE' : 'Re:set by PURE';
$total_reset_hours = $results4[0]['COL3'] ?? 0;

if($total_reset_hours != 0){
?>

<section class="yogacheck text-center resetpure my-70 mb-0">
    <h2 class="h2-title text-uppercase mb-1"><?php echo $reset_by_pure_label; ?></h2>
    <?php
        if($current_language == 'en'){?>
    <h3 class="h3-title mb-0">You benefitted from <?php echo $total_reset_hours;?> Re:sets — Calmer.
        Stronger. Better.</h3>
    <?php }else{?>
    <h3 class="h3-title mb-0">您參加了<?php echo $total_reset_hours;?>次Re:set──心靜 · 強身 · 更健康</h3>
    <?php }?>
</section>
<?php }

if($total_reset_hours == 0){?>
<section class="nogxyear pb-0">
    <div class="container custmpd">
        <div class="row">
            <div class="col-12 text-center mx-auto">
                <div class="nogxyear-content bg-lightbx">
                    <?php
                    if($current_language == 'en'){?>
                    <h2 class="h2-title">Re:set</h2>
                    <h3 class="h3-title-700">Make smart wellness next year's priority. <br>
                        Re:set to a Calmer. Stronger. Better. YOU.
                    </h3>
                    <div class="text-center findmore-btn mt-4">
                        <a href="https://www.re-set.com.hk/en/" class="bg-fit-clr">Find out more</a>
                    </div>
                    <?php }else{?>
                    <h2 class="h2-title">Re:set</h2>
                    <h3 class="h3-title-700">新一年，給自己充電！體驗 <br> Re:set，療癒身心！</h3>
                    <div class="text-center findmore-btn mt-4">
                        <a href="https://www.re-set.com.hk/tc/" class="bg-fit-clr">了解更多</a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php }?>
<button id="open-share">Share My Stats</button>

<div id="share-modal" style="display:none;">
    <div class="share-box">

        <h3>Choose what you want to share</h3>

        <div class="share-options">
            <button data-count="1">Share 1 Image</button>
            <button data-count="2">Share 2 Images</button>
            <button data-count="3">Share 3 Images</button>
        </div>

        <div id="preview-area"></div>

        <div class="share-actions" style="display:none;">
            <button id="share-instagram">Share to Instagram Story</button>
            <button id="share-native">Share via System</button>
            <button id="close-share">Close</button>
        </div>
    </div>
</div>

<!-- Hidden templates -->
<div id="share-templates" style="display:none;">
    <div class="share-card">Workout Overview</div>
    <div class="share-card">Class Summary</div>
    <div class="share-card">Achievements</div>
</div>
<style>
#share-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.share-box {
    background: #000;
    color: #fff;
    padding: 20px;
    width: 360px;
    border-radius: 12px;
}

.share-options button,
.share-actions button {
    margin: 6px;
    padding: 10px 16px;
    border-radius: 20px;
    border: none;
    background: #14e1dc;
    font-weight: 600;
}

.share-card {
    width: 1080px;
    height: 1920px;
    background: linear-gradient(135deg, #000, #111);
    margin: 10px auto;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 42px;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.11/dist/html-to-image.min.js"></script>

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

const modal = document.getElementById('share-modal');
const preview = document.getElementById('preview-area');
const actions = document.querySelector('.share-actions');

let generatedImages = [];

document.getElementById('open-share').onclick = () => {
    modal.style.display = 'flex';
};

document.getElementById('close-share').onclick = () => {
    modal.style.display = 'none';
    preview.innerHTML = '';
    generatedImages = [];
};

document.querySelectorAll('.share-options button').forEach(btn => {
    btn.onclick = async () => {
        const count = parseInt(btn.dataset.count, 10);
        await generateImages(count);
    };
});

async function generateImages(count) {

    preview.innerHTML = 'Generating preview...';
    generatedImages = [];

    const templates = document.querySelectorAll('#share-templates .share-card');

    for (let i = 0; i < count; i++) {
        const node = templates[i].cloneNode(true);
        document.body.appendChild(node);

        await new Promise(r => setTimeout(r, 100));

        const blob = await htmlToImage.toBlob(node, {
            cacheBust: true,
            skipFonts: true
        });

        document.body.removeChild(node);

        generatedImages.push(blob);

        const img = document.createElement('img');
        img.src = URL.createObjectURL(blob);
        img.style.width = '100%';
        preview.appendChild(img);
    }

    actions.style.display = 'block';
}

/* ---------- SHARE ---------- */

document.getElementById('share-native').onclick = async () => {

    const files = generatedImages.map((blob, i) =>
        new File([blob], `PURE-${i+1}.png`, {
            type: 'image/png'
        })
    );

    if (navigator.canShare && navigator.canShare({
            files
        })) {
        await navigator.share({
            title: 'My Workout Stats',
            text: 'Check out my workout stats 💪',
            files
        });
    } else {
        alert('Sharing not supported on this device');
    }
};

/* ---------- INSTAGRAM STORY (BEST POSSIBLE ON WEB) ---------- */

document.getElementById('share-instagram').onclick = async () => {

    if (!generatedImages.length) return;

    // Try clipboard (works on iOS Safari)
    try {
        const item = new ClipboardItem({
            'image/png': generatedImages[0]
        });
        await navigator.clipboard.write([item]);
        alert('Image copied! Paste it in Instagram Story.');
    } catch (e) {}

    // Open Instagram app
    window.location.href = 'instagram://story-camera';

    // Fallback
    setTimeout(() => {
        window.open('https://www.instagram.com/', '_blank');
    }, 500);
};
</script>

<script>
jQuery(document).ready(function($) {
    function setBannerImage() {
        var windowWidth = $(window).width();
        var mobileBanner = '<?php echo esc_url($mobile_banner); ?>';
        var desktopBanner = '<?php echo esc_url($desktop_banner); ?>';

        if (windowWidth <= 575) {
            // Set mobile banner image for small screens
            $('.workout-banner').css('background-image', 'url(' + mobileBanner + ')');
        } else {
            // Set desktop banner image for larger screens
            $('.workout-banner').css('background-image', 'url(' + desktopBanner + ')');
        }
    }

    // Run on page load
    setBannerImage();

    // Run when the window is resized
    $(window).resize(function() {
        setBannerImage();
    });

    $(".modal-wrap .btn-close").click(function() {
        $(".modal").modal("hide");
    });
});
</script>
<?php
}