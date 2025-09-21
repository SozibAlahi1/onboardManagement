<?php
namespace App;

use PDO;

class Repositories
{
    public function __construct(private PDO $pdo) {}

    public function insertSubmission(): int
    {
        $this->pdo->exec('INSERT INTO submissions () VALUES ()');
        return (int)$this->pdo->lastInsertId();
    }

    public function upsertContact(int $submissionId, array $d): void
    {
        $sql = 'INSERT INTO contacts (submission_id, contact_name, contact_title, email, phone, alternate_phone)
                VALUES (:sid, :name, :title, :email, :phone, :alt)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':sid' => $submissionId,
            ':name' => $d['যোগাযোগকারীর_নাম'] ?? '',
            ':title' => $d['যোগাযোগকারীর_পদবি'] ?? '',
            ':email' => $d['যোগাযোগকারীর_ইমেইল'] ?? '',
            ':phone' => $d['যোগাযোগকারীর_ফোন'] ?? '',
            ':alt' => $d['যোগাযোগকারীর_বিকল্প_ফোন'] ?? null,
        ]);
    }

    public function upsertCompanyProfile(int $submissionId, array $d): void
    {
        $sql = 'INSERT INTO company_profiles (
                    submission_id, company_name, website, facebook_page, whatsapp_number, address,
                    business_type, business_start_date, business_details, main_products_services, usp,
                    team_structure, financial_status, revenue_sources, seasonal_impact
                ) VALUES (
                    :sid, :company_name, :website, :facebook_page, :whatsapp_number, :address,
                    :business_type, :business_start_date, :business_details, :main_products_services, :usp,
                    :team_structure, :financial_status, :revenue_sources, :seasonal_impact
                )';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':sid' => $submissionId,
            ':company_name' => $d['প্রতিষ্ঠানের_নাম'] ?? '',
            ':website' => $d['প্রতিষ্ঠানের_ওয়েবসাইট'] ?? null,
            ':facebook_page' => $d['Facebook_পেজের_লিঙ্ক'] ?? null,
            ':whatsapp_number' => $d['WhatsApp_নম্বর'] ?? null,
            ':address' => $d['প্রতিষ্ঠানের_ঠিকানা'] ?? null,
            ':business_type' => $d['ব্যবসার_ধরণ'] ?? '',
            ':business_start_date' => $d['ব্যবসা_শুরুর_তারিখ'] ?? null,
            ':business_details' => $d['ব্যবসা_সম্পর্কিত_বিস্তারিত'] ?? null,
            ':main_products_services' => $d['প্রধান_পণ্য_বা_সেবা'] ?? null,
            ':usp' => $d['ব্যবসার_USP'] ?? null,
            ':team_structure' => $d['টিম_স্ট্রাকচার_ও_জনবল'] ?? null,
            ':financial_status' => $d['বর্তমান_আর্থিক_অবস্থা'] ?? null,
            ':revenue_sources' => $d['প্রধান_রাজস্বের_উৎস'] ?? null,
            ':seasonal_impact' => $d['সিজনাল_প্রভাব'] ?? null,
        ]);
    }

    public function upsertObjectives(int $submissionId, array $d): void
    {
        $sql = 'INSERT INTO objectives (
                    submission_id, business_vision, business_mission, core_values, main_challenges,
                    agency_expectations, marketing_kpis, past_failures
                ) VALUES (
                    :sid, :vision, :mission, :core_values, :challenges,
                    :expectations, :kpis, :failures
                )';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':sid' => $submissionId,
            ':vision' => $d['ব্যবসার_ভিশন'] ?? null,
            ':mission' => $d['ব্যবসার_মিশন'] ?? null,
            ':core_values' => $d['ব্যবসার_মূল্যবোধ'] ?? null,
            ':challenges' => $d['ব্যবসার_প্রধান_চ্যালেঞ্জ'] ?? null,
            ':expectations' => $d['এজেন্সি_থেকে_প্রত্যাশা'] ?? null,
            ':kpis' => $d['মার্কেটিং_লক্ষ্য_KPIs'] ?? null,
            ':failures' => $d['অতীতে_ব্যর্থতার_কারণ'] ?? null,
        ]);
    }

    public function insertGoals(int $submissionId, array $goals): void
    {
        if (empty($goals)) return;
        $sql = 'INSERT INTO goals (submission_id, goal_text) VALUES (:sid, :goal)';
        $stmt = $this->pdo->prepare($sql);
        foreach ($goals as $g) {
            $text = is_array($g) ? reset($g) : $g;
            if (!empty($text)) {
                $stmt->execute([':sid' => $submissionId, ':goal' => $text]);
            }
        }
    }

    public function upsertAudience(int $submissionId, array $d): void
    {
        $sql = 'INSERT INTO audiences (
                    submission_id, age_range, gender, location, profession_income, education, interests,
                    problems, social_media, online_behavior, multiple_target_audiences, purchase_influencers
                ) VALUES (
                    :sid, :age_range, :gender, :location, :profession_income, :education, :interests,
                    :problems, :social_media, :online_behavior, :multiple, :influencers
                )';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':sid' => $submissionId,
            ':age_range' => $d['টার্গেট_অডিয়েন্স_বয়সসীমা'] ?? null,
            ':gender' => $d['টার্গেট_অডিয়েন্স_লিঙ্গ'] ?? '',
            ':location' => $d['টার্গেট_অডিয়েন্স_অবস্থান'] ?? null,
            ':profession_income' => $d['টার্গেট_অডিয়েন্স_পেশা_আয়'] ?? null,
            ':education' => $d['টার্গেট_অডিয়েন্স_শিক্ষা'] ?? null,
            ':interests' => $d['টার্গেট_অডিয়েন্স_আগ্রহ_শখ'] ?? null,
            ':problems' => $d['টার্গেট_অডিয়েন্স_সমস্যা'] ?? null,
            ':social_media' => $d['টার্গেট_অডিয়েন্স_সোশ্যাল_মিডিয়া'] ?? null,
            ':online_behavior' => $d['টার্গেট_অডিয়েন্স_অনলাইন_আচরণ'] ?? null,
            ':multiple' => $d['একাধিক_টার্গেট_অডিয়েন্স'] ?? null,
            ':influencers' => $d['টার্গেট_অডিয়েন্স_ক্রয়_প্রভাবক'] ?? null,
        ]);
    }

    public function insertAudienceDevices(int $submissionId, array $devices): void
    {
        if (empty($devices)) return;
        $sql = 'INSERT INTO audience_devices (submission_id, device) VALUES (:sid, :device)';
        $stmt = $this->pdo->prepare($sql);
        foreach ($devices as $d) {
            $value = is_array($d) ? reset($d) : $d;
            if (!empty($value)) {
                $stmt->execute([':sid' => $submissionId, ':device' => $value]);
            }
        }
    }

    public function insertAudienceContentFormats(int $submissionId, array $formats): void
    {
        if (empty($formats)) return;
        $sql = 'INSERT INTO audience_content_formats (submission_id, content_format) VALUES (:sid, :fmt)';
        $stmt = $this->pdo->prepare($sql);
        foreach ($formats as $f) {
            $value = is_array($f) ? reset($f) : $f;
            if (!empty($value)) {
                $stmt->execute([':sid' => $submissionId, ':fmt' => $value]);
            }
        }
    }

    public function insertCompetitors(int $submissionId, array $names, array $websites): void
    {
        $sql = 'INSERT INTO competitors (submission_id, name, website) VALUES (:sid, :name, :website)';
        $stmt = $this->pdo->prepare($sql);
        $count = max(count($names), count($websites));
        for ($i = 0; $i < $count; $i++) {
            $name = $names[$i] ?? null;
            $site = $websites[$i] ?? null;
            if (!empty($name) || !empty($site)) {
                $stmt->execute([':sid' => $submissionId, ':name' => $name ?? '', ':website' => $site]);
            }
        }
    }

    public function upsertCompetitionAnalysis(int $submissionId, array $d): void
    {
        $sql = 'INSERT INTO competition_analysis (
                    submission_id, strengths_weaknesses, marketing_likes, product_differentiation,
                    competitor_target_audience, pricing_strategy, customer_service, market_share
                ) VALUES (
                    :sid, :sw, :ml, :pd, :cta, :ps, :cs, :ms
                )';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':sid' => $submissionId,
            ':sw' => $d['প্রতিযোগীদের_শক্তি_ও_দুর্বলতা'] ?? null,
            ':ml' => $d['প্রতিযোগীদের_মার্কেটিং_পছন্দ'] ?? null,
            ':pd' => $d['পণ্য_সেবার_বিশেষত্ব'] ?? null,
            ':cta' => $d['প্রতিযোগীদের_টার্গেট_অডিয়েন্স'] ?? null,
            ':ps' => $d['প্রতিযোগীদের_মূল্য_নির্ধারণ_কৌশল'] ?? null,
            ':cs' => $d['প্রতিযোগীদের_গ্রাহক_সেবা'] ?? null,
            ':ms' => $d['প্রতিযোগীদের_মার্কেট_শেয়ার'] ?? null,
        ]);
    }

    public function upsertMarketingBranding(int $submissionId, array $d): void
    {
        $sql = 'INSERT INTO marketing_branding (
                    submission_id, current_activities, successful_activities, less_successful_activities,
                    monthly_budget, ad_budget_allocation, in_house_team, previous_agency_experience,
                    analytics_tool_installed, analytics_tool_name, crm_used, crm_name, website_traffic_sources,
                    email_marketing_list, social_media_engagement, cac_understanding, marketing_automation_tools,
                    brand_guideline, brand_personality, existing_marketing_materials, blog_status, blog_frequency,
                    brand_tone_of_voice, content_themes_pillars, preferred_visuals
                ) VALUES (
                    :sid, :current, :success, :less_success, :monthly_budget, :ad_alloc, :inhouse, :prev_agency,
                    :analytics_installed, :analytics_name, :crm_used, :crm_name, :traffic, :email_list, :social,
                    :cac, :automation, :brand_guideline, :brand_personality, :existing, :blog_status, :blog_freq,
                    :tone, :themes, :visuals
                )';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':sid' => $submissionId,
            ':current' => $d['বর্তমান_মার্কেটিং_কার্যক্রম'] ?? null,
            ':success' => $d['সফল_কার্যক্রম_ও_কারণ'] ?? null,
            ':less_success' => $d['কম_সফল_কার্যক্রম_ও_কারণ'] ?? null,
            ':monthly_budget' => $d['মাসিক_মার্কেটিং_বাজেট'] ?? null,
            ':ad_alloc' => $d['বিজ্ঞাপন_বাজেট_বন্টন'] ?? null,
            ':inhouse' => $d['ইন_হাউস_মার্কেটিং_টিম'] ?? null,
            ':prev_agency' => $d['পূর্ববর্তী_এজেন্সি_অভিজ্ঞতা'] ?? null,
            ':analytics_installed' => isset($d['অ্যানালিটিক্স_টুল_ইনস্টল']) ? ($d['অ্যানালিটিক্স_টুল_ইনস্টল'] === 'হ্যাঁ' ? 1 : 0) : null,
            ':analytics_name' => $d['অ্যানালিটিক্স_টুল_নাম'] ?? null,
            ':crm_used' => isset($d['CRM_সিস্টেম_ব্যবহার']) ? ($d['CRM_সিস্টেম_ব্যবহার'] === 'হ্যাঁ' ? 1 : 0) : null,
            ':crm_name' => $d['CRM_সিস্টেম_নাম'] ?? null,
            ':traffic' => $d['ওয়েবসাইট_ট্র্যাফিক_সোর্স'] ?? null,
            ':email_list' => $d['ইমেইল_মার্কেটিং_লিস্ট'] ?? null,
            ':social' => $d['সোশ্যাল_মিডিয়া_এনগেজমেন্ট'] ?? null,
            ':cac' => $d['কাস্টমার_অ্যাকুইজিশন_কস্ট_ধারণা'] ?? null,
            ':automation' => $d['মার্কেটিং_অটোমেশন_টুলস'] ?? null,
            ':brand_guideline' => $d['ব্র্যান্ড_গাইডলাইন_আছে'] ?? '',
            ':brand_personality' => $d['ব্র্যান্ডের_ব্যক্তিত্ব'] ?? null,
            ':existing' => $d['বিদ্যমান_মার্কেটিং_উপকরণ'] ?? null,
            ':blog_status' => $d['ওয়েবসাইট_ব্লগ_স্ট্যাটাস'] ?? '',
            ':blog_freq' => $d['ব্লগ_প্রকাশের_ফ্রিকোয়েন্সি'] ?? null,
            ':tone' => $d['ব্র্যান্ডের_টোন_অফ_ভয়েস'] ?? null,
            ':themes' => $d['কনটেন্ট_থিম_পিলার'] ?? null,
            ':visuals' => $d['পছন্দের_ভিজ্যুয়াল'] ?? null,
        ]);
    }

    public function upsertBudgetTechFuture(int $submissionId, array $d): void
    {
        $sql = 'INSERT INTO budget_tech_future (
                    submission_id, project_budget, budget_priorities, results_timeline, reporting_preference,
                    need_website_admin_access, need_social_media_access, tracking_code_setup,
                    tracking_code_access_needed, future_products_services, tech_usage_plans, ecommerce_payment_experience
                ) VALUES (
                    :sid, :project_budget, :budget_priorities, :results_timeline, :reporting_preference,
                    :need_admin, :need_social, :tracking_setup, :tracking_access, :future_ps, :tech_plans, :ecom_exp
                )';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':sid' => $submissionId,
            ':project_budget' => $d['প্রজেক্ট_বাজেট'] ?? null,
            ':budget_priorities' => $d['বাজেট_অগ্রাধিকার'] ?? null,
            ':results_timeline' => $d['ফলাফল_দেখার_সময়সীমা'] ?? null,
            ':reporting_preference' => $d['রিপোর্টিং_পছন্দ'] ?? '',
            ':need_admin' => $d['ওয়েবসাইট_অ্যাডমিন_অ্যাক্সেস'] ?? '',
            ':need_social' => $d['সোশ্যাল_মিডিয়া_অ্যাক্সেস'] ?? '',
            ':tracking_setup' => $d['ট্র্যাকিং_কোড_সেটআপ'] ?? '',
            ':tracking_access' => $d['ট্র্যাকিং_কোড_অ্যাক্সেস_প্রয়োজন'] ?? null,
            ':future_ps' => $d['ভবিষ্যৎ_পণ্য_সেবা'] ?? null,
            ':tech_plans' => $d['প্রযুক্তি_ব্যবহার_পরিকল্পনা'] ?? null,
            ':ecom_exp' => $d['ইকমার্স_পেমেন্ট_অভিজ্ঞতা'] ?? '',
        ]);
    }

    public function upsertFinalization(int $submissionId, array $d): void
    {
        $sql = 'INSERT INTO finalization (
                    submission_id, additional_info, how_found_agency, questions_for_agency, submission_date, terms_agreed
                ) VALUES (
                    :sid, :addl, :how_found, :questions, :date, :terms
                )';
        $stmt = $this->pdo->prepare($sql);
        $terms = isset($d['terms_agreement']) ? 1 : 0;
        $date = $d['স্বাক্ষরের_তারিখ'] ?? null;
        $stmt->execute([
            ':sid' => $submissionId,
            ':addl' => $d['অতিরিক্ত_তথ্য'] ?? null,
            ':how_found' => $d['এজেন্সি_সম্পর্কে_জানার_উৎস'] ?? null,
            ':questions' => $d['এজেন্সির_কাছে_প্রশ্ন'] ?? null,
            ':date' => $date,
            ':terms' => $terms,
        ]);
    }
}


