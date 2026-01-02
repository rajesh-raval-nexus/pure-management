<?php
/* Template Name: Member Workout New Template 2025 */

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
    $title = ($current_language == 'en') ? 'Your 2025 Pure Holistic<br>Wellness Wrapped' : 'PURE身心旅程<br>2025年度回顧';

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
                <?php if (!empty($title)) { ?>
                <h1 class="title-h1 mb-4">
                    <?php echo $title; ?>                    
                </h1>
                <?php } ?>

                <?php if (!empty($sdescription)) { ?>
                <p class="desc-text">
                    <?php echo $sdescription; ?>
                </p>
                <?php } ?>
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

<?php
    $number_of_friends_you_referred = ($results[0][$table_column_name_prefix.'_03_friends_referr_total_friends_referred_to_pure']) ?: 0;
    $days_with_pure = ($results[0][$table_column_name_prefix.'_02_days_with_pure_pure_1st_start_date_2025_11_04']) ?: 0;
    $workout_percentage_in_top = ($results2[0]['avg_workout_days']) ?: 0;
    $title = ($current_language == 'en') ? 'Your Journey with PURE' : 'PURE 的探索之旅';
    $days_label = $journey['no_of_days_label'];
    $days_title = ($current_language == 'en')? 'Total Days with PURE' : '累積投入PURE的精彩日子';    
    $cumu_title = ($current_language == 'en') ? 'Total Friends Referred to PURE' : '累積推薦給PURE的朋友';
    $workout_days_2025 = ($results[0][$table_column_name_prefix.'_04_total_workout__total_attended_sessions_in_2025']) ?: 0;    
    $timel_title = ($current_language == 'en') ? 'Total PURE days in 2025' : 'Total PURE days in 2025';

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
<section class="journey-report">
    <div class="journey-inner">
        <span class="hexagon hex-pink">
            <img src="<?= get_image_url('workout-2026/hexagon-pink.svg') ?>" class="img-fluid">
        </span>
        <div class="journey-image">
            <img src="<?= get_image_url('workout-2026/your-journey.webp') ?>" alt="person" />
        </div>

        <div class="journey-content">
                <?php if (!empty($title)){ ?>
                    <h2 class="title-h2"><?php echo $title; ?></h2>
                <?php } ?>
            <div class="stat-grid">
                <div class="stat-card">                    
                    <div class="stat-label"><?php echo $days_title; ?></div>
                    <div class="stat-value"><?php echo $days_with_pure; ?></div>                    
                </div>

                <div class="stat-card">
                    <div class="stat-label"><?php echo $cumu_title; ?></div>
                    <div class="stat-value"><?php echo $number_of_friends_you_referred; ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-label"><?php echo $timel_title; ?></div>
                    <div class="stat-note"><?php echo $total_workout_day_msg; ?></div>
                    <div class="stat-value"><?php echo $workout_days_2025;?></div>
                </div>
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
                <div class="stat-card">
                    <div class="stat-label"><?php echo $first_class_title; ?></div>
                    <div class="stat-value"><?php echo $class_you_attended_first_time; ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-label"><?php echo $first_location_title; ?></div>
                    <div class="stat-value"><?php echo $location_you_visited_first_time; ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-label"><?php echo $longest_streak_title; ?></div>
                    <div class="stat-note"><?php echo $longest_streak_message; ?></div>
                    <div class="stat-value"><?php echo $longest_workout_streak; ?></div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
        $when_you_workout_label = ($current_language == 'en') ? 'YOUR ACTIVE HOURS' : ' 時段分佈';
        $morning_workout_6_to_9am = ($results[0][$table_column_name_prefix.'_08_morning_workou_percentage_between_6am_and_9am']) ?: $no_msg;
        $workout_9am_to_5pm = ($results[0][$table_column_name_prefix.'_09_workouts_9am_5_percentage_between_9am_and_5pm']) ?: $no_msg;
        $workout_after_5pm = ($results[0][$table_column_name_prefix.'_10_workouts_after_percentage_after_5pm']) ?: $no_msg;
        $morning_label = ($current_language == 'en') ? 'Morning (6–9am)': '晨間時段（6–9am）';
        $daytime_label = ($current_language == 'en') ? 'Daytime (9am–5pm)': '日間時段（9am–5pm）';
        $evening_label = ($current_language == 'en') ? 'Evening / Night (after 5pm)': '晚間時段（5pm後）';
    ?>
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
                <h2 class="title-h2"><?php echo $when_you_workout_label; ?> </h2>
            </div>
        </div>
        <div class="row">
            <div class="pill-stats text-center">
                <div class="pill-item">
                    <div class="pill-wrap">
                        <div class="pill-fill" style="--fill:<?php  echo is_numeric($morning_workout_6_to_9am) ? $morning_workout_6_to_9am.'%' : $morning_workout_6_to_9am; ?>;">
                            <span class="wave"></span>
                        </div>
                    </div>
                    <p class="title-h3 mt-3 mb-2"><?php echo $morning_label; ?></p>
                    <p class="title-h4"><?php  echo is_numeric($morning_workout_6_to_9am) ? $morning_workout_6_to_9am.'%' : $morning_workout_6_to_9am; ?></p>
                </div>

                <div class="pill-item">
                    <div class="pill-wrap">
                        <div class="pill-fill full" style="--fill:<?php echo is_numeric($workout_9am_to_5pm) ? $workout_9am_to_5pm.'%' : $workout_9am_to_5pm; ?>;">
                            <span class="wave"></span>
                        </div>
                    </div>
                    <p class="title-h3 mt-3 mb-2"><?php echo $daytime_label; ?></p>
                    <p class="title-h4"><?php echo is_numeric($workout_9am_to_5pm) ? $workout_9am_to_5pm.'%' : $workout_9am_to_5pm; ?></p>
                </div>

                <div class="pill-item">
                    <div class="pill-wrap">
                        <div class="pill-fill" style="--fill:<?php echo is_numeric($workout_after_5pm) ? $workout_after_5pm.'%' : $workout_after_5pm; ?>;">
                            <span class="wave"></span>
                        </div>
                    </div>
                    <p class="title-h3 mt-3 mb-2"><?php echo $evening_label; ?></p>
                    <p class="title-h4"><?php echo is_numeric($workout_after_5pm) ? $workout_after_5pm.'%' : $workout_after_5pm; ?></p>
                </div>
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

    // 3-letter English month (Jan, Feb, Mar...)
    $short_month = date("M", $timestamp);

    // Chinese month mapping (based on English short month)
    $months_cn = [
        'Jan' => '1月',
        'Feb' => '2月',
        'Mar' => '3月',
        'Apr' => '4月',
        'May' => '5月',
        'Jun' => '6月',
        'Jul' => '7月',
        'Aug' => '8月',
        'Sep' => '9月',
        'Oct' => '10月',
        'Nov' => '11月',
        'Dec' => '12月',
    ];

    // Final output based on language
    $month_output = ($current_language === 'en')
        ? $short_month
        : ($months_cn[$short_month] ?? $short_month);

?>
<section class="cmw-1320 most-active-month py-80">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="title-h2 mb-4 text-center"><?php echo $most_active_month_label; ?></h2>
                <div class="month-bg position-relative">
                    <img src="<?= get_image_url('workout-2026/active-month-bg.webp') ?>" alt="Your most active month"
                        class="img-fluid w-100">
                    <div class="month-info text-center">
                        <div class="position-relative">
                            <img src="<?= get_image_url('workout-2026/calendar.svg') ?>" alt="calendar">
                            <p class="month-h3"><?php echo $month_output; ?></p>
                        </div>
                        <?php
                        if($current_language == 'en'){?>
                            <p class="title-h4 font-700 text-pink"><?php echo $total_days_check_in; ?> Active Days</p>
                        <?php } else { ?>
                            <p class="title-h4 font-700 text-pink"><?php echo '本月活躍日數：'. $total_days_check_in .'日'; ?></p>
                        <?php } ?>                        
                    </div>
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

?><section class="cmw-1320 yoga-sessions sessions-bg py-80">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="title-h2 text-white"><?php echo $yoga_class_label; ?></h2>
            </div>
        </div>
        <div class="col-12 glass-bx">
            <div class="row align-items-stretch">
                <div class="col-lg-5">
                    <div class="glass-card">
                        <h3 class="title-h3 mb-2 text-black"><?php echo $class_attended_label; ?></h3>
                        <p class="title-h4 mb-2"><?php echo $total_attended_yoga_classes; ?></p>
                        <p class="p-desc">
                            <?php echo $inspiration_text; ?>
                        </p>
                    </div>
                <?php
                    $year_on_year_yoga_comparison_label = ($current_language == 'en') ? 'Year-on-year Yoga Comparison' : '瑜珈課程年度比較';
                    $yoga_percentage_2024 = ($results[0][$table_column_name_prefix.'_14_yoga_classes_2_number_of_yoga_classes_attended_in_2024']) ?: 0;
                    $yoga_percentage_2025 = ($results[0][$table_column_name_prefix.'_14_yoga_classes_2_number_of_yoga_classes_attended_in_2025']) ?: 0;
                ?>
                    <div class="glass-card">
                        <h3 class="title-h3 mb-2 text-black"><?php echo $year_on_year_yoga_comparison_label; ?></h3>
                        <div class="d-flex gap-5 align-items-center mb-2">
                            <div>
                                <p class="title-h3 mb-2 text-black">2024</p>
                                <p class="title-h4"><?php echo $yoga_percentage_2024; ?></p>
                            </div>
                            <div class="ms-4">
                                <p class="title-h3 mb-2 text-black">2025</p>
                                <p class="title-h4">
                                    <?php echo $yoga_percentage_2025; ?>
                                    <svg class="<?php echo ( $yoga_percentage_2024 > $yoga_percentage_2025 ) ? 'down-arrow': 'up-arrow'?> <?php echo  ( trim( $yoga_percentage_2024 ) == trim( $yoga_percentage_2025 ) ) ? ' d-none': ''; ?>" xmlns="http://www.w3.org/2000/svg" width="18" height="20"
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
                            <?php
                            if($current_language == 'en'){?>
                            “Yoga is a journey of the self, through the self, to the self.”
                            <?php } else { ?>
                            “瑜伽是通往自我的旅程。”
                            <?php } ?>
                        </p>
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
                        <div class="glass-card mb-0">
                            <h3 class="title-h3 mb-2 text-black"><?php echo $top_3_yoga_classes_label; ?></h3>
                            <p class="title-h4">
                                <?php foreach ($yoga_classes as $class): ?>
                                    <?php echo $class['name']; ?><br>
                                <?php endforeach; ?>                            
                            </p>
                        </div>
                    <?php } ?>
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

<?php 

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
                    'img'  => get_field('listing_photo', $post->ID) ?: $default_teacher_img,
                ];
            }
        }
    }

if(!empty($yoga_teachers)){ ?>

<section class="cmw-1320 top-teachers py-80 pb-0">
    <span class="hexagon top-center">
        <img src="<?= get_image_url('workout-2026/hexagon-teal.svg') ?>" class="img-fluid">
    </span>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="title-h2"><?= $top_3_yoga_teachers_label ?></h2>
            </div>
        </div>

        <div class="row align-items-end">
            <?php 
            $teacher_count = 1;
            foreach($yoga_teachers as $t){ ?>
            <div class="col-md-4 text-center teacher-card <?php 
                if($teacher_count == 2){ echo 'hex-second'; } ?>">
                <span class="hexagon hex-right <?php 
                if($teacher_count == 2){ echo 'hex-second'; } ?>">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid ">
                </span>
                <span class="hexagon hex-left <?php 
                if($teacher_count == 2){ echo 'hex-second'; } ?>">
                    <img src="<?= get_image_url('workout-2026/hexagon-white.svg') ?>" class="img-fluid hex-left">
                </span>
                <h3 class="title-h3"><?= esc_html($t['name']) ?></h3>
                <div class="teacher-image">
                    <img src="<?= esc_url($t['img']) ?>" alt="<?= esc_attr($t['name']) ?>" class="img-fluid">
                </div>
            </div>
            <?php $teacher_count++; } ?>
        </div>        
    </div>
</section>
<?php } ?>
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
<section class="cmw-1320 top-teachers py-80 pb-0">
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
<section class="cmw-1320 top-teachers py-80 pb-0">
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