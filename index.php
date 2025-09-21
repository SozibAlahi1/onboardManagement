<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ক্লায়েন্ট অনবোর্ডিং ফরম (মাল্টি-স্টেপ)</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <style>
        body {
            font-family: 'SolaimanLipi', Arial, sans-serif;
            margin: 0;
            padding: 10px; /* Reduced body padding for mobile */
            background-color: #eef2f7;
            color: #334155;
            line-height: 1.6; /* Slightly adjusted line height */
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 900px;
            margin: 15px auto; 
            background-color: #ffffff;
            padding: 20px 25px; 
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1), 0 5px 10px rgba(0,0,0,0.05);
        }
        .form-header h1 {
            font-size: 1.6rem; /* Adjusted for mobile */
            color: #1e40af; 
            font-weight: 700;
            margin-bottom: 15px;
            text-align: center;
        }
        .progress-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 0;
            list-style: none;
            background-color: #f0f4f8; 
            border-radius: 25px; 
            padding: 5px; 
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
        }
        .progress-bar .step {
            padding: 8px 10px; 
            border: none; 
            border-radius: 20px; 
            color: #4b5563;
            text-align: center;
            flex-grow: 1;
            margin: 0 2px; 
            font-size: 0.75em; /* Smaller font for desktop steps */
            cursor: pointer;
            transition: background-color 0.4s ease, color 0.4s ease, transform 0.2s ease;
            font-weight: 500;
            white-space: nowrap; /* Prevent text wrapping in steps */
            overflow: hidden;
            text-overflow: ellipsis; /* Add ellipsis if text is too long */
        }
        .progress-bar .step .step-text { /* Span for text to hide on mobile */
            display: inline;
        }
        .progress-bar .step.active {
            background: linear-gradient(135deg, #3b82f6, #2563eb); 
            color: white;
            font-weight: 700;
            transform: scale(1.05); 
            box-shadow: 0 2px 5px rgba(59, 130, 246, 0.3);
        }
        .progress-bar .step.completed {
            background-color: #a7f3d0; 
            color: #047857; 
        }
        .progress-bar .step:not(.active):not(.completed):hover {
            background-color: #e5e7eb;
        }
        .mobile-progress-info { /* For "Step X of Y: Title" on mobile */
            display: none; /* Hidden by default, shown on mobile */
            text-align: center;
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 15px;
            padding: 8px;
            background-color: #e0e7ff;
            border-radius: 6px;
        }

        .form-step {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
            border: 1px solid #e5e7eb;
            padding: 15px; /* Reduced padding for mobile */
            border-radius: 8px;
            margin-top: 10px;
            background-color: #fdfdff; 
        }
        .form-step.active-step {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-step h2.section-title {
            font-size: 1.3rem; /* Adjusted for mobile */
            color: #1e3a8a;
            font-weight: 600;
            border-bottom: 2px solid #60a5fa; 
            padding-bottom: 6px;
            margin-top: 0;
            margin-bottom: 18px;
        }
        .form-step h2.section-title i {
            margin-right: 8px;
        }
        .form-step h3.sub-title {
            font-size: 1.1rem; /* Adjusted for mobile */
            color: #111827;
            margin-top: 15px;
            margin-bottom: 10px;
            padding-bottom: 3px;
            border-bottom: 1px dashed #cbd5e1;
        }
        .input-group {
            margin-bottom: 18px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px; 
            font-weight: 600;
            color: #1f2937;
            font-size: 0.9rem; /* Adjusted for mobile */
        }
        .input-group label i.label-icon {
            margin-right: 6px;
            color: #3b82f6;
            width: 16px;
            text-align: center;
        }
        .input-group input[type="text"],
        .input-group input[type="email"],
        .input-group input[type="tel"],
        .input-group input[type="date"],
        .input-group input[type="url"],
        .input-group textarea,
        .input-group select {
            width: 100%;
            padding: 9px 10px; 
            border: 1px solid #b0bec5; 
            border-radius: 6px; 
            box-sizing: border-box;
            font-family: 'SolaimanLipi', Arial, sans-serif;
            font-size: 0.9rem; /* Adjusted for mobile */
            color: #1f2937;
            background-color: #f8fafc; 
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .input-group input:focus,
        .input-group textarea:focus,
        .input-group select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); /* Smaller shadow */
            outline: none;
            background-color: #fff;
        }
        .input-group textarea {
            min-height: 80px;
            resize: vertical;
        }
        .radio-group, .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px; /* Reduced gap */
            padding-top: 3px;
        }
        .radio-group label, .checkbox-group label {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 7px 10px; 
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: #f9fafb;
            font-weight: normal;
            font-size: 0.85rem; /* Adjusted for mobile */
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .radio-group label:hover, .checkbox-group label:hover {
            background-color: #e5e7eb;
            border-color: #9ca3af;
        }
        .radio-group input[type="radio"], .checkbox-group input[type="checkbox"] {
            margin-right: 6px;
            accent-color: #2563eb;
            width: 0.9em;
            height: 0.9em;
        }
        .form-navigation {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .form-navigation button {
            padding: 9px 20px; 
            border-radius: 6px;
            font-size: 0.95rem; /* Adjusted for mobile */
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: 'SolaimanLipi', Arial, sans-serif; 
        }
        .form-navigation button.prev-btn {
            background-color: #6b7280;
            color: white;
        }
        .form-navigation button.prev-btn:hover {
            background-color: #4b5563;
            transform: translateY(-1px);
        }
        .form-navigation button.next-btn,
        .form-navigation button.submit-btn {
            background-color: #2563eb;
            color: white;
        }
        .form-navigation button.next-btn:hover,
        .form-navigation button.submit-btn:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
        }
        .form-navigation button.hidden {
            display: none;
        }
        .form-navigation button:disabled {
            background-color: #d1d5db;
            cursor: not-allowed;
            transform: none;
        }
        .error-message {
            color: #ef4444; 
            font-size: 0.8rem; /* Adjusted for mobile */
            margin-top: 3px;
        }
        input.invalid, textarea.invalid, select.invalid {
            border-color: #ef4444 !important;
            background-color: #fee2e2; 
        }
        .conditional-field {
            display: none; 
            margin-top: 8px;
            padding: 8px;
            background-color: #f9fafb;
            border: 1px dashed #d1d5db;
            border-radius: 4px;
        }
        .add-more-btn {
            background-color: #10b981; 
            color: white;
            padding: 5px 10px; /* Compact button */
            font-size: 0.8rem; /* Adjusted for mobile */
            border-radius: 4px;
            cursor: pointer;
            border: none;
            margin-top: 6px;
            transition: background-color 0.3s;
        }
        .add-more-btn:hover {
            background-color: #059669; 
        }
        .dynamic-item-group {
            padding: 8px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            margin-bottom: 8px;
            background-color: #fdfdff;
        }
        .dynamic-item-group input[type="text"], .dynamic-item-group input[type="url"] {
            margin-bottom: 4px;
        }

        /* Mobile specific styles for progress bar and layout */
        @media (max-width: 768px) { /* Tablet and below */
            .container {
                padding: 20px 20px;
            }
            .form-header h1 {
                font-size: 1.5rem;
            }
            .progress-bar .step {
                font-size: 0.7em;
                padding: 7px 8px;
            }
             .progress-bar .step .step-text {
                display: none; /* Hide text on tablet, show only number or icon */
            }
            .progress-bar .step::before { /* Show step number */
                content: attr(data-step);
                font-weight: bold;
            }
            .progress-bar .step.active::before {
                 content: attr(data-step); /* Ensure active step number is also shown */
            }
             .progress-bar .step.active .step-text { /* Optionally show text for active step if space allows */
                display: inline; /* Or none, depending on preference */
                margin-left: 4px;
            }

        }

        @media (max-width: 640px) { /* Mobile specific */
            body {
                padding: 5px;
            }
            .container {
                margin: 10px auto;
                padding: 15px;
            }
            .form-header h1 {
                font-size: 1.4rem;
            }
            .progress-bar {
                display: none; /* Hide the default progress bar */
            }
            .mobile-progress-info {
                display: block; /* Show the mobile specific progress info */
            }
            .form-step h2.section-title {
                font-size: 1.2rem;
            }
            .form-step h3.sub-title {
                font-size: 1rem;
            }
            .input-group label {
                font-size: 0.85rem;
            }
            .input-group input, .input-group textarea, .input-group select {
                font-size: 0.85rem;
                padding: 8px 10px;
            }
            .radio-group label, .checkbox-group label {
                font-size: 0.8rem;
                padding: 6px 8px;
            }
            .form-navigation button {
                font-size: 0.9rem;
                padding: 8px 16px;
            }
            .add-more-btn {
                font-size: 0.75rem;
                padding: 4px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-file-signature"></i> ক্লায়েন্ট অনবোর্ডিং ফরম</h1>
        </div>

        <ul class="progress-bar">
            <li class="step active" data-step="1" data-step-title="পরিচিতি"><span class="step-text">পরিচিতি</span></li>
            <li class="step" data-step="2" data-step-title="ব্যবসা"><span class="step-text">ব্যবসা</span></li>
            <li class="step" data-step="3" data-step-title="লক্ষ্য"><span class="step-text">লক্ষ্য</span></li>
            <li class="step" data-step="4" data-step-title="গ্রাহক ও প্রতিযোগী"><span class="step-text">গ্রাহক ও প্রতিযোগী</span></li>
            <li class="step" data-step="5" data-step-title="মার্কেটিং"><span class="step-text">মার্কেটিং</span></li>
            <li class="step" data-step="6" data-step-title="বাজেট ও প্রযুক্তি"><span class="step-text">বাজেট ও প্রযুক্তি</span></li>
            <li class="step" data-step="7" data-step-title="চূড়ান্তর"><span class="step-text">চূড়ান্তর</span></li>
        </ul>
        <div class="mobile-progress-info"></div> <form id="clientOnboardingForm" novalidate>
            <div class="form-step active-step" data-step="1">
                <h2 class="section-title"><i class="fas fa-play-circle"></i> ধাপ ১: প্রাথমিক পরিচিতি</h2>
                <p>আপনার এবং আপনার ব্যবসা সম্পর্কে কিছু মৌলিক তথ্য দিন।</p>

                <div class="input-group">
                    <label for="company_name"><i class="fas fa-building label-icon"></i> প্রতিষ্ঠানের নাম:</label>
                    <input type="text" id="company_name" name="প্রতিষ্ঠানের_নাম" placeholder="আপনার প্রতিষ্ঠানের সম্পূর্ণ নাম" required>
                </div>
                <div class="input-group">
                    <label><i class="fas fa-user-tie label-icon"></i> প্রধান যোগাযোগকারী:</label>
                    <input type="text" name="যোগাযোগকারীর_নাম" placeholder="নাম" required style="margin-bottom: 10px;">
                    <input type="text" name="যোগাযোগকারীর_পদবি" placeholder="পদবি" required>
                </div>
                <div class="input-group">
                    <label for="contact_email"><i class="fas fa-envelope label-icon"></i> যোগাযোগের ইমেইল:</label>
                    <input type="email" id="contact_email" name="যোগাযোগকারীর_ইমেইল" placeholder="সঠিক ইমেইল ঠিকানা দিন" required>
                </div>
                <div class="input-group">
                    <label for="contact_phone"><i class="fas fa-phone label-icon"></i> যোগাযোগের ফোন:</label>
                    <input type="tel" id="contact_phone" name="যোগাযোগকারীর_ফোন" placeholder="ফোন নম্বর দিন" required>
                </div>
                <div class="input-group">
                    <label for="contact_alternative_phone"><i class="fas fa-mobile-alt label-icon"></i> বিকল্প ফোন (যদি থাকে):</label>
                    <input type="tel" id="contact_alternative_phone" name="যোগাযোগকারীর_বিকল্প_ফোন" placeholder="বিকল্প ফোন নম্বর">
                </div>
            </div>

            <div class="form-step" data-step="2">
                <h2 class="section-title"><i class="fas fa-briefcase"></i> ধাপ ২: ব্যবসার বিস্তারিত</h2>
                <p>আপনার ব্যবসার কাঠামো, কার্যক্রম এবং প্রধান দিকগুলো সম্পর্কে জানান।</p>
                
                <div class="input-group">
                    <label for="company_website"><i class="fas fa-globe label-icon"></i> প্রতিষ্ঠানের ওয়েবসাইট:</label>
                    <input type="url" id="company_website" name="প্রতিষ্ঠানের_ওয়েবসাইট" placeholder="https://www.example.com">
                </div>
                <div class="input-group">
                    <label for="facebook_page"><i class="fab fa-facebook-square label-icon"></i> Facebook পেজের লিঙ্ক:</label>
                    <input type="url" id="facebook_page" name="Facebook_পেজের_লিঙ্ক" placeholder="https://www.facebook.com/yourpage">
                </div>
                 <div class="input-group">
                    <label for="whatsapp_number"><i class="fab fa-whatsapp label-icon"></i> WhatsApp নম্বর (১১ সংখ্যা):</label>
                    <input type="tel" id="whatsapp_number" name="WhatsApp_নম্বর" placeholder="সঠিক ১১ সংখ্যার WhatsApp নম্বর দিন">
                </div>
                <div class="input-group">
                    <label for="company_address"><i class="fas fa-map-marker-alt label-icon"></i> প্রতিষ্ঠানের ঠিকানা:</label>
                    <textarea id="company_address" name="প্রতিষ্ঠানের_ঠিকানা" placeholder="সম্পূর্ণ ঠিকানা লিখুন (বাসা/হোল্ডিং, রোড, এলাকা, শহর, পোস্ট কোড)"></textarea>
                </div>
                <div class="input-group">
                    <label for="business_type"><i class="fas fa-briefcase label-icon"></i> ব্যবসার ধরণ:</label>
                    <select id="business_type" name="ব্যবসার_ধরণ">
                        <option value="">-- নির্বাচন করুন --</option>
                        <option value="একক_মালিকানা">একক মালিকানা (Sole Proprietorship)</option>
                        <option value="পার্টনারশিপ">পার্টনারশিপ (Partnership)</option>
                        <option value="প্রাইভেট_লিমিটেড">প্রাইভেট লিমিটেড কোম্পানি</option>
                        <option value="পাবলিক_লিমিটেড">পাবলিক লিমিটেড কোম্পানি</option>
                        <option value="এনজিও">এনজিও/অলাভজনক সংস্থা</option>
                        <option value="অন্যান্য">অন্যান্য</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="business_start_date"><i class="fas fa-calendar-check label-icon"></i> ব্যবসা শুরুর তারিখ/বছর:</label>
                    <input type="text" id="business_start_date" name="ব্যবসা_শুরুর_তারিখ" placeholder="যেমন: জানুয়ারি ২০১৫ বা ১৫/০১/২০১৫">
                </div>
                <div class="input-group">
                    <label for="business_details"><i class="fas fa-align-left label-icon"></i> আপনার ব্যবসা সম্পর্কে বিস্তারিত বলুন:</label>
                    <textarea id="business_details" name="ব্যবসা_সম্পর্কিত_বিস্তারিত" placeholder="আপনারা কী করেন, কাদের জন্য করেন এবং কীভাবে করেন?"></textarea>
                </div>
                <div class="input-group">
                    <label for="main_products_services"><i class="fas fa-cubes label-icon"></i> আপনার প্রধান পণ্য বা সেবাগুলো:</label>
                    <textarea id="main_products_services" name="প্রধান_পণ্য_বা_সেবা" placeholder="যদি একাধিক থাকে, কমা দিয়ে তালিকা দিন"></textarea>
                </div>
                <div class="input-group">
                    <label for="business_usp"><i class="fas fa-lightbulb label-icon"></i> ব্যবসার মূল শক্তি/ইউনিক সেলিং প্রপোজিশন (USP):</label>
                    <textarea id="business_usp" name="ব্যবসার_USP" placeholder="কেন গ্রাহকরা আপনার প্রতিযোগী রেখে আপনাকে বেছে নেবে?"></textarea>
                </div>
                <div class="input-group">
                    <label for="team_structure"><i class="fas fa-users-cog label-icon"></i> টিম স্ট্রাকচার ও জনবল:</label>
                    <textarea id="team_structure" name="টিম_স্ট্রাকচার_ও_জনবল" placeholder="আপনার প্রতিষ্ঠানে বর্তমানে কতজন কর্মী আছেন এবং প্রধান বিভাগগুলো কী কী?"></textarea>
                </div>
                <div class="input-group">
                    <label for="financial_status"><i class="fas fa-hand-holding-usd label-icon"></i> বর্তমান আর্থিক অবস্থা (সংক্ষেপে):</label>
                    <textarea id="financial_status" name="বর্তমান_আর্থিক_অবস্থা" placeholder="ব্যবসার বর্তমান আর্থিক অবস্থা কেমন? লাভজনক, ব্রেক-ইভেন, নাকি লোকসানে আছে?"></textarea>
                </div>
                <div class="input-group">
                    <label for="revenue_sources"><i class="fas fa-chart-pie label-icon"></i> প্রধান রাজস্বের উৎস:</label>
                    <textarea id="revenue_sources" name="প্রধান_রাজস্বের_উৎস" placeholder="আপনার ব্যবসার প্রধান রাজস্ব কোন পণ্য/সেবা থেকে আসে?"></textarea>
                </div>
                <div class="input-group">
                    <label for="seasonal_impact"><i class="fas fa-cloud-sun-rain label-icon"></i> ব্যবসায় কোনো সিজনাল প্রভাব আছে কি?</label>
                    <textarea id="seasonal_impact" name="সিজনাল_প্রভাব" placeholder="বছরের কোন সময়ে পণ্যের চাহিদা বাড়ে বা কমে? বিস্তারিত জানান।"></textarea>
                </div>
            </div>

            <div class="form-step" data-step="3">
                <h2 class="section-title"><i class="fas fa-flag-checkered"></i> ধাপ ৩: লক্ষ্য ও উদ্দেশ্য</h2>
                <p>আপনার ব্যবসার ভিশন, মিশন এবং আমাদের সাথে কাজ করে কী অর্জন করতে চান তা জানান।</p>

                <div class="input-group">
                    <label for="business_vision"><i class="fas fa-binoculars label-icon"></i> আপনার ব্যবসার ভিশন (Vision):</label>
                    <textarea id="business_vision" name="ব্যবসার_ভিশন" placeholder="দীর্ঘমেয়াদে আপনার ব্যবসাকে কোথায় দেখতে চান?"></textarea>
                </div>
                 <div class="input-group">
                    <label for="business_mission"><i class="fas fa-bullseye label-icon"></i> আপনার ব্যবসার মিশন (Mission):</label>
                    <textarea id="business_mission" name="ব্যবসার_মিশন" placeholder="ভিশন অর্জনের জন্য আপনারা বর্তমানে কী করছেন?"></textarea>
                </div>
                <div class="input-group">
                    <label for="core_values"><i class="fas fa-gem label-icon"></i> আপনার ব্যবসার মূল্যবোধ (Core Values):</label>
                    <textarea id="core_values" name="ব্যবসার_মূল্যবোধ" placeholder="আপনার ব্যবসার প্রধান কয়েকটি মূল্যবোধ উল্লেখ করুন"></textarea>
                </div>
                <div class="input-group">
                    <label for="main_challenges"><i class="fas fa-exclamation-triangle label-icon"></i> আপনার ব্যবসার প্রধান চ্যালেঞ্জগুলো কী কী?</label>
                    <textarea id="main_challenges" name="ব্যবসার_প্রধান_চ্যালেঞ্জ" placeholder="বর্তমানে কোন কোন প্রধান বাধার সম্মুখীন হচ্ছেন?"></textarea>
                </div>
                <div class="input-group">
                    <label><i class="fas fa-list-ol label-icon"></i> আগামী ৬-১২ মাসের মধ্যে আপনার ব্যবসার প্রধান লক্ষ্যগুলো:</label>
                    <div id="goals_container">
                        <div class="dynamic-item-group">
                            <input type="text" name="লক্ষ্য[]" placeholder="প্রথম লক্ষ্য">
                        </div>
                    </div>
                    <button type="button" class="add-more-btn" onclick="addGoalField()"><i class="fas fa-plus-circle"></i> আরও লক্ষ্য যুক্ত করুন</button>
                </div>
                <div class="input-group">
                    <label for="agency_expectations"><i class="fas fa-handshake label-icon"></i> আমাদের এজেন্সি থেকে আপনার প্রধান প্রত্যাশাগুলো:</label>
                    <textarea id="agency_expectations" name="এজেন্সি_থেকে_প্রত্যাশা" placeholder="আমাদের কাছ থেকে কী ধরনের ফলাফল বা সহায়তা আশা করছেন?"></textarea>
                </div>
                <div class="input-group">
                    <label for="marketing_kpis"><i class="fas fa-chart-line label-icon"></i> আপনি কি কোনো নির্দিষ্ট মার্কেটিং লক্ষ্য (KPIs) পরিমাপ করতে চান?</label>
                    <textarea id="marketing_kpis" name="মার্কেটিং_লক্ষ্য_KPIs" placeholder="যেমন: ওয়েবসাইটে মাসিক ভিজিটর সংখ্যা, লিড জেনারেশন সংখ্যা, কনভার্সন রেট ইত্যাদি"></textarea>
                </div>
                <div class="input-group">
                    <label for="past_failures"><i class="fas fa-history label-icon"></i> অতীতে কোনো মার্কেটিং লক্ষ্য অর্জনে ব্যর্থ হয়েছেন কি? হলে, তার কারণ কী?</label>
                    <textarea id="past_failures" name="অতীতে_ব্যর্থতার_কারণ" placeholder="ব্যর্থতার কারণ এবং আপনার পর্যবেক্ষণ"></textarea>
                </div>
            </div>

            <div class="form-step" data-step="4">
                <h2 class="section-title"><i class="fas fa-users"></i> ধাপ ৪: টার্গেট অডিয়েন্স ও প্রতিযোগী</h2>
                <p>আপনার আদর্শ গ্রাহক কারা এবং বাজারে আপনার প্রধান প্রতিযোগী কারা?</p>
                
                <h3 class="sub-title"><i class="fas fa-user-circle"></i> টার্গেট অডিয়েন্স</h3>
                <div class="input-group">
                    <label for="audience_age_range"><i class="far fa-calendar-alt label-icon"></i> বয়সসীমা:</label>
                    <input type="text" id="audience_age_range" name="টার্গেট_অডিয়েন্স_বয়সসীমা" placeholder="যেমন: ২৫-৪৫ বছর">
                </div>
                <div class="input-group">
                    <label><i class="fas fa-venus-mars label-icon"></i> লিঙ্গ:</label>
                    <div class="radio-group">
                        <label><input type="radio" name="টার্গেট_অডিয়েন্স_লিঙ্গ" value="পুরুষ"> পুরুষ</label>
                        <label><input type="radio" name="টার্গেট_অডিয়েন্স_লিঙ্গ" value="মহিলা"> মহিলা</label>
                        <label><input type="radio" name="টার্গেট_অডিয়েন্স_লিঙ্গ" value="উভয়"> উভয়</label>
                        <label><input type="radio" name="টার্গেট_অডিয়েন্স_লিঙ্গ" value="উল্লেখ_করতে_চাই_না"> উল্লেখ করতে চাই না</label>
                    </div>
                </div>
                <div class="input-group">
                    <label for="audience_location"><i class="fas fa-map-pin label-icon"></i> ভৌগোলিক অবস্থান:</label>
                    <input type="text" id="audience_location" name="টার্গেট_অডিয়েন্স_অবস্থান" placeholder="যেমন: ঢাকা শহর, বা নির্দিষ্ট এলাকা">
                </div>
                <div class="input-group">
                    <label for="audience_profession_income"><i class="fas fa-briefcase label-icon"></i> পেশা/আয়ের স্তর:</label>
                    <input type="text" id="audience_profession_income" name="টার্গেট_অডিয়েন্স_পেশা_আয়" placeholder="যেমন: চাকুরীজীবী, ব্যবসায়ী, মধ্যম আয়">
                </div>
                 <div class="input-group">
                    <label for="audience_education"><i class="fas fa-graduation-cap label-icon"></i> শিক্ষা:</label>
                    <input type="text" id="audience_education" name="টার্গেট_অডিয়েন্স_শিক্ষা" placeholder="যেমন: স্নাতক, স্নাতকোত্তর">
                </div>
                 <div class="input-group">
                    <label for="audience_interests"><i class="fas fa-heart label-icon"></i> আগ্রহ ও শখ:</label>
                    <textarea id="audience_interests" name="টার্গেট_অডিয়েন্স_আগ্রহ_শখ" placeholder="যেমন: ভ্রমণ, প্রযুক্তি, বই পড়া"></textarea>
                </div>
                <div class="input-group">
                    <label for="audience_problems"><i class="fas fa-comment-dots label-icon"></i> তারা কোন ধরনের সমস্যার সম্মুখীন হয় যা আপনার পণ্য/সেবা সমাধান করতে পারে?</label>
                    <textarea id="audience_problems" name="টার্গেট_অডিয়েন্স_সমস্যা" placeholder="তাদের প্রধান সমস্যাগুলো কী?"></textarea>
                </div>
                <div class="input-group">
                    <label for="audience_social_media"><i class="fab fa-sourcetree label-icon"></i> তারা সাধারণত কোন সোশ্যাল মিডিয়া প্ল্যাটফর্ম ব্যবহার করে?</label>
                    <input type="text" id="audience_social_media" name="টার্গেট_অডিয়েন্স_সোশ্যাল_মিডিয়া" placeholder="যেমন: ফেসবুক, ইনস্টাগ্রাম, ইউটিউব, লিংকডইন">
                </div>
                <div class="input-group">
                    <label for="audience_online_behavior"><i class="fas fa-mouse-pointer label-icon"></i> তাদের অনলাইন আচরণের ধরণ কেমন?</label>
                    <textarea id="audience_online_behavior" name="টার্গেট_অডিয়েন্স_অনলাইন_আচরণ" placeholder="যেমন: তারা কি অনলাইনে কেনাকাটা করে, রিভিউ পড়ে, নাকি তথ্য খোঁজে?"></textarea>
                </div>
                 <div class="input-group">
                    <label for="multiple_target_audiences"><i class="fas fa-users-slash label-icon"></i> আপনার কি একাধিক ধরনের টার্গেট অডিয়েন্স আছে?</label>
                    <textarea id="multiple_target_audiences" name="একাধিক_টার্গেট_অডিয়েন্স" placeholder="থাকলে, তাদের সম্পর্কে সংক্ষেপে বলুন"></textarea>
                </div>
                <div class="input-group">
                    <label><i class="fas fa-mobile-alt label-icon"></i> আপনার গ্রাহকরা সাধারণত কোন ডিভাইস ব্যবহার করে অনলাইন ব্রাউজ করে?</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="টার্গেট_অডিয়েন্স_ডিভাইস[]" value="মোবাইল"> মোবাইল</label>
                        <label><input type="checkbox" name="টার্গেট_অডিয়েন্স_ডিভাইস[]" value="ল্যাপটপ"> ল্যাপটপ</label>
                        <label><input type="checkbox" name="টার্গেট_অডিয়েন্স_ডিভাইস[]" value="ডেস্কটপ"> ডেস্কটপ</label>
                        <label><input type="checkbox" name="টার্গেট_অডিয়েন্স_ডিভাইস[]" value="ট্যাবলেট"> ট্যাবলেট</label>
                    </div>
                </div>
                <div class="input-group">
                    <label for="audience_purchase_influencers"><i class="fas fa-balance-scale label-icon"></i> আপনার গ্রাহকদের ক্রয় সিদ্ধান্তকে কোন বিষয়গুলো সবচেয়ে বেশি প্রভাবিত করে?</label>
                    <textarea id="audience_purchase_influencers" name="টার্গেট_অডিয়েন্স_ক্রয়_প্রভাবক" placeholder="যেমন: দাম, গুণমান, রিভিউ, ব্র্যান্ড পরিচিতি, বন্ধুর সুপারিশ"></textarea>
                </div>
                <div class="input-group">
                    <label><i class="fas fa-photo-video label-icon"></i> আপনার গ্রাহকদের পছন্দের কনটেন্ট ফরম্যাট কী?</label>
                     <div class="checkbox-group">
                        <label><input type="checkbox" name="টার্গেট_অডিয়েন্স_কনটেন্ট_ফরম্যাট[]" value="ভিডিও"> ভিডিও</label>
                        <label><input type="checkbox" name="টার্গেট_অডিয়েন্স_কনটেন্ট_ফরম্যাট[]" value="ব্লগ_পোস্ট"> ব্লগ পোস্ট</label>
                        <label><input type="checkbox" name="টার্গেট_অডিয়েন্স_কনটেন্ট_ফরম্যাট[]" value="ইনফোগ্রাফিক"> ইনফোগ্রাফিক</label>
                        <label><input type="checkbox" name="টার্গেট_অডিয়েন্স_কনটেন্ট_ফরম্যাট[]" value="পডকাস্ট"> পডকাস্ট</label>
                        <label><input type="checkbox" name="টার্গেট_অডিয়েন্স_কনটেন্ট_ফরম্যাট[]" value="ছবি"> ছবি</label>
                        <label><input type="checkbox" name="টার্গেট_অডিয়েন্স_কনটেন্ট_ফরম্যাট[]" value="কেস_স্টাডি"> কেস স্টাডি</label>
                        <label><input type="checkbox" name="টার্গেট_অডিয়েন্স_কনটেন্ট_ফরম্যাট[]" value="ওয়েবিনার"> ওয়েবিনার</label>
                    </div>
                </div>

                <h3 class="sub-title"><i class="fas fa-search-dollar"></i> প্রতিযোগী বিশ্লেষণ</h3>
                <div class="input-group">
                    <label><i class="fas fa-users label-icon"></i> আপনার প্রধান প্রতিযোগী কারা?</label>
                    <div id="competitors_container">
                        <div class="dynamic-item-group">
                            <input type="text" name="প্রতিযোগী_নাম[]" placeholder="প্রতিযোগীর নাম" style="margin-bottom: 5px;">
                            <input type="url" name="প্রতিযোগী_ওয়েবসাইট[]" placeholder="ওয়েবসাইট (https://example.com)">
                        </div>
                    </div>
                    <button type="button" class="add-more-btn" onclick="addCompetitorField()"><i class="fas fa-user-plus"></i> আরও প্রতিযোগী যুক্ত করুন</button>
                </div>
                 <div class="input-group">
                    <label for="competitor_strengths_weaknesses"><i class="fas fa-thumbs-up label-icon"></i><i class="fas fa-thumbs-down label-icon" style="margin-left:-5px;"></i> প্রতিযোগীদের প্রধান শক্তি ও দুর্বলতাগুলো:</label>
                    <textarea id="competitor_strengths_weaknesses" name="প্রতিযোগীদের_শক্তি_ও_দুর্বলতা" placeholder="প্রতিযোগীদের শক্তি এবং দুর্বলতার দিকগুলো উল্লেখ করুন"></textarea>
                </div>
                <div class="input-group">
                    <label for="competitor_marketing_likes"><i class="far fa-lightbulb label-icon"></i> তাদের মার্কেটিং কার্যক্রমের কোন দিকগুলো আপনার ভালো লাগে বা আপনি অনুসরণ করতে চান?</label>
                    <textarea id="competitor_marketing_likes" name="প্রতিযোগীদের_মার্কেটিং_পছন্দ" placeholder="তাদের কোন মার্কেটিং কৌশল আপনার পছন্দ?"></textarea>
                </div>
                <div class="input-group">
                    <label for="product_differentiation"><i class="fas fa-star label-icon"></i> আপনার পণ্য/সেবা প্রতিযোগীদের থেকে কীভাবে আলাদা বা উন্নত?</label>
                    <textarea id="product_differentiation" name="পণ্য_সেবার_বিশেষত্ব" placeholder="আপনার বিশেষত্ব কী?"></textarea>
                </div>
                 <div class="input-group">
                    <label for="competitor_target_audience"><i class="fas fa-eye label-icon"></i> প্রতিযোগীদের টার্গেট অডিয়েন্স:</label>
                    <textarea id="competitor_target_audience" name="প্রতিযোগীদের_টার্গেট_অডিয়েন্স" placeholder="আপনার প্রতিযোগীরা কাদের টার্গেট করে বলে আপনি মনে করেন?"></textarea>
                </div>
                <div class="input-group">
                    <label for="competitor_pricing_strategy"><i class="fas fa-tags label-icon"></i> প্রতিযোগীদের মূল্য নির্ধারণ কৌশল:</label>
                    <textarea id="competitor_pricing_strategy" name="প্রতিযোগীদের_মূল্য_নির্ধারণ_কৌশল" placeholder="তাদের মূল্য নির্ধারণ কৌশল সম্পর্কে আপনার পর্যবেক্ষণ কী?"></textarea>
                </div>
                <div class="input-group">
                    <label for="competitor_customer_service"><i class="fas fa-comments label-icon"></i> প্রতিযোগীদের গ্রাহক সেবা:</label>
                    <textarea id="competitor_customer_service" name="প্রতিযোগীদের_গ্রাহক_সেবা" placeholder="তাদের গ্রাহক সেবা সম্পর্কে আপনি কী জানেন বা শুনেছেন?"></textarea>
                </div>
                <div class="input-group">
                    <label for="competitor_market_share"><i class="fas fa-chart-area label-icon"></i> মার্কেট শেয়ার (আনুমানিক):</label>
                    <textarea id="competitor_market_share" name="প্রতিযোগীদের_মার্কেট_শেয়ার" placeholder="আপনার প্রধান প্রতিযোগীদের আনুমানিক মার্কেট শেয়ার কেমন?"></textarea>
                </div>
            </div>

            <div class="form-step" data-step="5">
                <h2 class="section-title"><i class="fas fa-bullhorn"></i> ধাপ ৫: মার্কেটিং ও ব্র্যান্ডিং</h2>
                <p>আপনার বর্তমান মার্কেটিং কৌশল, বাজেট এবং ব্র্যান্ডের পরিচিতি সম্পর্কে তথ্য দিন।</p>

                <h3 class="sub-title"><i class="fas fa-tasks"></i> বর্তমান মার্কেটিং কার্যক্রম</h3>
                <div class="input-group">
                    <label for="current_marketing_activities"><i class="fas fa-tasks label-icon"></i> আপনি বর্তমানে কী কী মার্কেটিং কার্যক্রম পরিচালনা করছেন?</label>
                    <textarea id="current_marketing_activities" name="বর্তমান_মার্কেটিং_কার্যক্রম" placeholder="যেমন: ফেসবুক মার্কেটিং, গুগল অ্যাডস, এসইও, ইমেইল মার্কেটিং, কনটেন্ট মার্কেটিং ইত্যাদি"></textarea>
                </div>
                <div class="input-group">
                    <label><i class="fas fa-check-double label-icon"></i> এই কার্যক্রমগুলোর মধ্যে কোনটি সবচেয়ে সফল এবং কোনটি কম সফল হয়েছে? কেন?</label>
                    <label for="successful_activities" style="font-weight:normal; margin-top:10px;">সফল কার্যক্রম ও কারণ:</label>
                    <textarea id="successful_activities" name="সফল_কার্যক্রম_ও_কারণ" placeholder="সফল কার্যক্রম এবং তার কারণ" style="margin-bottom:10px;"></textarea>
                    <label for="less_successful_activities" style="font-weight:normal;">কম সফল কার্যক্রম ও কারণ:</label>
                    <textarea id="less_successful_activities" name="কম_সফল_কার্যক্রম_ও_কারণ" placeholder="কম সফল কার্যক্রম এবং তার কারণ"></textarea>
                </div>
                 <div class="input-group">
                    <label for="monthly_marketing_budget"><i class="fas fa-wallet label-icon"></i> আপনার বর্তমান মাসিক মার্কেটিং বাজেট কত?</label>
                    <input type="text" id="monthly_marketing_budget" name="মাসিক_মার্কেটিং_বাজেট" placeholder="যদি নির্দিষ্ট থাকে, আনুমানিক পরিমাণ উল্লেখ করুন">
                </div>
                <div class="input-group">
                    <label for="ad_budget_allocation"><i class="fas fa-ad label-icon"></i> বিজ্ঞাপন বাজেট বন্টন:</label>
                    <textarea id="ad_budget_allocation" name="বিজ্ঞাপন_বাজেট_বন্টন" placeholder="বর্তমানে বিজ্ঞাপনের বাজেট বিভিন্ন চ্যানেলে কীভাবে বন্টন করা হয়? (যেমন: ফেসবুক ৭০%, গুগল ৩০%)"></textarea>
                </div>
                <div class="input-group">
                    <label for="in_house_marketing_team"><i class="fas fa-users-cog label-icon"></i> আপনার কি কোনো ইন-হাউস মার্কেটিং টিম বা ব্যক্তি আছে? থাকলে, তাদের ভূমিকা কী?</label>
                    <textarea id="in_house_marketing_team" name="ইন_হাউস_মার্কেটিং_টিম" placeholder="টিম সদস্য এবং তাদের দায়িত্ব"></textarea>
                </div>
                <div class="input-group">
                    <label for="previous_agency_experience"><i class="far fa-handshake label-icon"></i> আপনি কি আগে কোনো মার্কেটিং এজেন্সি বা ফ্রিল্যান্সারের সাথে কাজ করেছেন? অভিজ্ঞতা কেমন ছিল?</label>
                    <textarea id="previous_agency_experience" name="পূর্ববর্তী_এজেন্সি_অভিজ্ঞতা" placeholder="পূর্ববর্তী অভিজ্ঞতা (ভালো/মন্দ) এবং কারণ"></textarea>
                </div>
                <div class="input-group">
                    <label><i class="fas fa-tools label-icon"></i> আপনার কি কোনো মার্কেটিং অ্যানালিটিক্স টুল ইনস্টল করা আছে?</label>
                     <div class="radio-group">
                        <label><input type="radio" name="অ্যানালিটিক্স_টুল_ইনস্টল" value="হ্যাঁ" onclick="toggleConditionalField('analytics_tool_details', true)"> হ্যাঁ</label>
                        <label><input type="radio" name="অ্যানালিটিক্স_টুল_ইনস্টল" value="না" onclick="toggleConditionalField('analytics_tool_details', false)"> না</label>
                    </div>
                    <div id="analytics_tool_details" class="conditional-field">
                         <label for="analytics_tool_name" style="font-weight:normal;">থাকলে, কোন টুলস?</label>
                         <input type="text" id="analytics_tool_name" name="অ্যানালিটিক্স_টুল_নাম" placeholder="Google Analytics, Facebook Pixel ইত্যাদি">
                    </div>
                </div>
                <div class="input-group">
                    <label><i class="fas fa-headset label-icon"></i> আপনি কি বর্তমানে কোনো CRM সিস্টেম ব্যবহার করেন?</label>
                     <div class="radio-group">
                        <label><input type="radio" name="CRM_সিস্টেম_ব্যবহার" value="হ্যাঁ" onclick="toggleConditionalField('crm_system_details', true)"> হ্যাঁ</label>
                        <label><input type="radio" name="CRM_সিস্টেম_ব্যবহার" value="না" onclick="toggleConditionalField('crm_system_details', false)"> না</label>
                    </div>
                    <div id="crm_system_details" class="conditional-field">
                        <label for="CRM_সিস্টেম_নাম" style="font-weight:normal;">থাকলে, কোনটি?</label>
                        <input type="text" id="CRM_সিস্টেম_নাম" name="CRM_সিস্টেম_নাম" placeholder="HubSpot, Zoho CRM ইত্যাদি">
                    </div>
                </div>
                <div class="input-group">
                    <label for="website_traffic_sources"><i class="fas fa-traffic-light label-icon"></i> আপনার ওয়েবসাইটের বর্তমান ট্র্যাফিক সোর্সগুলো কী কী?</label>
                    <textarea id="website_traffic_sources" name="ওয়েবসাইট_ট্র্যাফিক_সোর্স" placeholder="যেমন: অর্গানিক সার্চ, সোশ্যাল মিডিয়া, ডিরেক্ট, রেফারেল, পেইড অ্যাডস"></textarea>
                </div>
                <div class="input-group">
                    <label for="email_marketing_list"><i class="fas fa-envelope-open-text label-icon"></i> ইমেইল মার্কেটিং লিস্ট:</label>
                    <textarea id="email_marketing_list" name="ইমেইল_মার্কেটিং_লিস্ট" placeholder="আপনার কি কোনো ইমেইল লিস্ট আছে? থাকলে তার আকার এবং কীভাবে সংগ্রহ করা হয়েছে?"></textarea>
                </div>
                <div class="input-group">
                    <label for="social_media_engagement"><i class="fas fa-share-alt label-icon"></i> সোশ্যাল মিডিয়া এনগেজমেন্ট:</label>
                    <textarea id="social_media_engagement" name="সোশ্যাল_মিডিয়া_এনগেজমেন্ট" placeholder="আপনার সোশ্যাল মিডিয়াতে ফলোয়ার সংখ্যা এবং গড় এনগেজমেন্ট (লাইক, কমেন্ট, শেয়ার) কেমন?"></textarea>
                </div>
                <div class="input-group">
                    <label for="cac_understanding"><i class="fas fa-comments-dollar label-icon"></i> কাস্টমার অ্যাকুইজিশন কস্ট (CAC) সম্পর্কে ধারণা:</label>
                    <textarea id="cac_understanding" name="কাস্টমার_অ্যাকুইজিশন_কস্ট_ধারণা" placeholder="একজন নতুন গ্রাহক পেতে আনুমানিক কত খরচ হয়, সে সম্পর্কে কোনো ধারণা আছে কি?"></textarea>
                </div>
                <div class="input-group">
                    <label for="marketing_automation_tools"><i class="fas fa-sync-alt label-icon"></i> মার্কেটিং অটোমেশন টুলস:</label>
                    <textarea id="marketing_automation_tools" name="মার্কেটিং_অটোমেশন_টুলস" placeholder="কোনো মার্কেটিং অটোমেশন টুলস ব্যবহার করেন কি? যেমন: HubSpot, Mailchimp অটোমেশন"></textarea>
                </div>
                
                <h3 class="sub-title"><i class="fas fa-palette"></i> ব্র্যান্ডিং ও কনটেন্ট</h3>
                 <div class="input-group">
                    <label><i class="fas fa-book-reader label-icon"></i> আপনার কি কোনো ব্র্যান্ড গাইডলাইন (Brand Guideline) আছে?</label>
                    <div class="radio-group">
                        <label><input type="radio" name="ব্র্যান্ড_গাইডলাইন_আছে" value="হ্যাঁ"> হ্যাঁ (থাকলে শেয়ার করুন)</label>
                        <label><input type="radio" name="ব্র্যান্ড_গাইডলাইন_আছে" value="না"> না</label>
                    </div>
                </div>
                <div class="input-group">
                    <label for="brand_personality"><i class="fas fa-theater-masks label-icon"></i> আপনার ব্র্যান্ডের ব্যক্তিত্ব (Brand Personality) কেমন?</label>
                    <input type="text" id="brand_personality" name="ব্র্যান্ডের_ব্যক্তিত্ব" placeholder="যেমন: বন্ধুত্বপূর্ণ, প্রফেশনাল, উদ্ভাবনী, নির্ভরযোগ্য ইত্যাদি">
                </div>
                <div class="input-group">
                    <label for="existing_marketing_materials"><i class="fas fa-folder-open label-icon"></i> আপনার কি বিদ্যমান কোনো মার্কেটিং উপকরণ বা কনটেন্ট আছে?</label>
                    <textarea id="existing_marketing_materials" name="বিদ্যমান_মার্কেটিং_উপকরণ" placeholder="যেমন: ব্রোশিওর, ব্লগ পোস্ট, ভিডিও, সোশ্যাল মিডিয়া পোস্ট ইত্যাদি (থাকলে লিঙ্ক বা স্যাম্পল দিন)"></textarea>
                </div>
                <div class="input-group">
                    <label><i class="fas fa-blog label-icon"></i> আপনার ওয়েবসাইটে কি ব্লগ সেকশন আছে?</label>
                     <div class="radio-group">
                        <label><input type="radio" name="ওয়েবসাইট_ব্লগ_স্ট্যাটাস" value="হ্যাঁ" onclick="toggleConditionalField('blog_frequency_details', true)"> হ্যাঁ</label>
                        <label><input type="radio" name="ওয়েবসাইট_ব্লগ_স্ট্যাটাস" value="না" onclick="toggleConditionalField('blog_frequency_details', false)"> না</label>
                    </div>
                    <div id="blog_frequency_details" class="conditional-field">
                         <label for="ব্লগ_প্রকাশের_ফ্রিকোয়েন্সি" style="font-weight:normal;">আপনি কি নিয়মিত কনটেন্ট প্রকাশ করেন? থাকলে, কতদিন পর পর?</label>
                         <input type="text" id="ব্লগ_প্রকাশের_ফ্রিকোয়েন্সি" name="ব্লগ_প্রকাশের_ফ্রিকোয়েন্সি" placeholder="যেমন: সাপ্তাহিক, মাসিক">
                    </div>
                </div>
                <div class="input-group">
                    <label for="brand_tone_of_voice"><i class="fas fa-microphone-alt label-icon"></i> আপনার ব্র্যান্ডের টোন অফ ভয়েস কেমন হওয়া উচিত?</label>
                    <input type="text" id="brand_tone_of_voice" name="ব্র্যান্ডের_টোন_অফ_ভয়েস" placeholder="যেমন: ফরমাল, ইনফরমাল, রসাত্মক, সহানুভূতিশীল">
                </div>
                <div class="input-group">
                    <label for="content_themes_pillars"><i class="fas fa-object-group label-icon"></i> আপনার কি কোনো নির্দিষ্ট কনটেন্ট থিম বা পিলার আছে যা আপনি ফোকাস করতে চান?</label>
                    <textarea id="content_themes_pillars" name="কনটেন্ট_থিম_পিলার" placeholder="যেমন: সাসটেইনেবিলিটি, স্বাস্থ্যকর জীবনযাপন, প্রযুক্তিগত উদ্ভাবন"></textarea>
                </div>
                <div class="input-group">
                    <label for="preferred_visuals"><i class="fas fa-image label-icon"></i> আপনার গ্রাহকদের আকৃষ্ট করার জন্য কোন ধরনের ভিজ্যুয়াল (ছবি/ভিডিও) সবচেয়ে ভালো কাজ করে?</label>
                    <textarea id="preferred_visuals" name="পছন্দের_ভিজ্যুয়াল" placeholder="যেমন: বাস্তব জীবনের ছবি, অ্যানিমেটেড ভিডিও, ইনফোগ্রাফিক্স, ব্যবহারকারী-তৈরি কনটেন্ট"></textarea>
                </div>
            </div>

            <div class="form-step" data-step="6">
                <h2 class="section-title"><i class="fas fa-cogs"></i> ধাপ ৬: বাজেট, প্রযুক্তি ও ভবিষ্যৎ পরিকল্পনা</h2>
                <p>আপনার বাজেট, প্রযুক্তিগত দিক এবং ভবিষ্যৎ পরিকল্পনা সম্পর্কে আমাদের জানান।</p>

                <h3 class="sub-title"><i class="fas fa-coins"></i> বাজেট ও প্রত্যাশা</h3>
                <div class="input-group">
                    <label for="project_budget"><i class="fas fa-money-bill-wave label-icon"></i> আমাদের সাথে কাজ করার জন্য আপনার আনুমানিক মাসিক বা প্রজেক্টভিত্তিক বাজেট কত?</label>
                    <input type="text" id="project_budget" name="প্রজেক্ট_বাজেট" placeholder="এটি আমাদের সাধ্যের মধ্যে সেরা সমাধানটি প্রস্তাব করতে সাহায্য করবে">
                </div>
                <div class="input-group">
                    <label for="budget_priorities"><i class="fas fa-sliders-h label-icon"></i> বাজেট বরাদ্দের ক্ষেত্রে আপনার কোনো নির্দিষ্ট অগ্রাধিকার আছে কি?</label>
                    <textarea id="budget_priorities" name="বাজেট_অগ্রাধিকার" placeholder="যেমন: ফেসবুক বিজ্ঞাপনে বেশি খরচ করতে চান, বা কনটেন্ট তৈরিতে ইত্যাদি"></textarea>
                </div>
                <div class="input-group">
                    <label for="results_timeline"><i class="fas fa-hourglass-half label-icon"></i> ফলাফল দেখার জন্য আপনি সাধারণত কত সময় অপেক্ষা করতে ইচ্ছুক?</label>
                    <input type="text" id="results_timeline" name="ফলাফল_দেখার_সময়সীমা" placeholder="কিছু মার্কেটিং কৌশলে ফলাফল আসতে সময় লাগে">
                </div>
                <div class="input-group">
                    <label for="reporting_preference"><i class="fas fa-file-alt label-icon"></i> আপনি কীভাবে আমাদের কাজের অগ্রগতি এবং ফলাফল সম্পর্কে রিপোর্ট পেতে চান?</label>
                    <select id="reporting_preference" name="রিপোর্টিং_পছন্দ">
                        <option value="">-- নির্বাচন করুন --</option>
                        <option value="সাপ্তাহিক">সাপ্তাহিক</option>
                        <option value="পাক্ষিক">পাক্ষিক</option>
                        <option value="মাসিক">মাসিক</option>
                        <option value="প্রয়োজন_অনুযায়ী">প্রয়োজন অনুযায়ী</option>
                    </select>
                </div>

                <h3 class="sub-title"><i class="fas fa-laptop-code"></i> টেকনিক্যাল তথ্য</h3>
                <p style="font-size: 0.9em; font-style: italic; color: #4b5563; margin-bottom: 15px;">(এই তথ্যগুলো নিরাপদে এবং শুধুমাত্র প্রয়োজনীয় ক্ষেত্রে ব্যবহার করা হবে। প্রয়োজনে আমরা আলাদাভাবে এই তথ্যগুলো সংগ্রহ করব।)</p>
                <div class="input-group">
                    <label><i class="fas fa-laptop-code label-icon"></i> আপনার ওয়েবসাইটের অ্যাডমিন প্যানেলে কি আমাদের অ্যাক্সেসের প্রয়োজন হবে?</label>
                     <div class="radio-group">
                        <label><input type="radio" name="ওয়েবসাইট_অ্যাডমিন_অ্যাক্সেস" value="হ্যাঁ"> হ্যাঁ</label>
                        <label><input type="radio" name="ওয়েবসাইট_অ্যাডমিন_অ্যাক্সেস" value="না"> না</label>
                        <label><input type="radio" name="ওয়েবসাইট_অ্যাডমিন_অ্যাক্সেস" value="পরে_জানাবো"> পরে জানাবো</label>
                    </div>
                </div>
                <div class="input-group">
                    <label><i class="fab fa-facebook-square label-icon"></i><i class="fab fa-instagram label-icon" style="margin-left:-5px;"></i><i class="fab fa-google label-icon" style="margin-left:-5px;"></i> আপনার সোশ্যাল মিডিয়া অ্যাকাউন্টগুলোতে কি আমাদের অ্যাডমিন/এডিটর অ্যাক্সেসের প্রয়োজন হবে?</label>
                     <div class="radio-group">
                        <label><input type="radio" name="সোশ্যাল_মিডিয়া_অ্যাক্সেস" value="হ্যাঁ"> হ্যাঁ</label>
                        <label><input type="radio" name="সোশ্যাল_মিডিয়া_অ্যাক্সেস" value="না"> না</label>
                        <label><input type="radio" name="সোশ্যাল_মিডিয়া_অ্যাক্সেস" value="পরে_জানাবো"> পরে জানাবো</label>
                    </div>
                </div>
                <div class="input-group">
                    <label><i class="fas fa-chart-bar label-icon"></i> আপনার কি গুগল অ্যানালিটিক্স, ফেসবুক পিক্সেল বা অন্য কোনো ট্র্যাকিং কোড ওয়েবসাইটে সেটআপ করা আছে?</label>
                     <div class="radio-group">
                        <label><input type="radio" name="ট্র্যাকিং_কোড_সেটআপ" value="হ্যাঁ" onclick="toggleConditionalField('tracking_code_access_details', true)"> হ্যাঁ</label>
                        <label><input type="radio" name="ট্র্যাকিং_কোড_সেটআপ" value="না" onclick="toggleConditionalField('tracking_code_access_details', false)"> না</label>
                    </div>
                    <div id="tracking_code_access_details" class="conditional-field">
                        <label for="ট্র্যাকিং_কোড_অ্যাক্সেস_প্রয়োজন" style="font-weight:normal;">থাকলে, সেগুলোর অ্যাক্সেস কি আমাদের প্রয়োজন হবে?</label>
                        <input type="text" id="ট্র্যাকিং_কোড_অ্যাক্সেস_প্রয়োজন" name="ট্র্যাকিং_কোড_অ্যাক্সেস_প্রয়োজন" placeholder="হ্যাঁ/না/আলোচনা সাপেক্ষ">
                    </div>
                </div>

                <h3 class="sub-title"><i class="fas fa-rocket"></i> ভবিষ্যৎ পরিকল্পনা ও প্রযুক্তি</h3>
                 <div class="input-group">
                    <label for="future_products_services"><i class="fas fa-box-open label-icon"></i> আগামী ২-৩ বছরে আপনার ব্যবসার জন্য কোনো নতুন পণ্য বা সেবা আনার পরিকল্পনা আছে কি?</label>
                    <textarea id="future_products_services" name="ভবিষ্যৎ_পণ্য_সেবা" placeholder="নতুন পণ্য/সেবা এবং সম্ভাব্য লঞ্চের সময় সম্পর্কে জানান"></textarea>
                </div>
                <div class="input-group">
                    <label for="tech_usage_plans"><i class="fas fa-microchip label-icon"></i> আপনার ব্যবসায় প্রযুক্তি ব্যবহারের বর্তমান অবস্থা এবং ভবিষ্যৎ পরিকল্পনা কী?</label>
                    <textarea id="tech_usage_plans" name="প্রযুক্তি_ব্যবহার_পরিকল্পনা" placeholder="কোন কোন প্রযুক্তি ব্যবহার করছেন এবং ভবিষ্যতে কী ব্যবহারের পরিকল্পনা আছে?"></textarea>
                </div>
                <div class="input-group">
                    <label><i class="fas fa-credit-card label-icon"></i> ই-কমার্স বা অনলাইন পেমেন্ট সিস্টেম ব্যবহারে আপনার অভিজ্ঞতা বা আগ্রহ কেমন?</label>
                     <div class="radio-group">
                        <label><input type="radio" name="ইকমার্স_পেমেন্ট_অভিজ্ঞতা" value="বর্তমানে_ব্যবহার_করছি"> হ্যাঁ, বর্তমানে ব্যবহার করছি</label>
                        <label><input type="radio" name="ইকমার্স_পেমেন্ট_অভিজ্ঞতা" value="ভবিষ্যতে_আগ্রহী"> হ্যাঁ, ভবিষ্যতে আগ্রহী</label>
                        <label><input type="radio" name="ইকমার্স_পেমেন্ট_অভিজ্ঞতা" value="না_আগ্রহ_নেই"> না, আগ্রহ নেই</label>
                    </div>
                </div>
            </div>

            <div class="form-step" data-step="7">
                <h2 class="section-title"><i class="fas fa-check-circle"></i> ধাপ ৭: অতিরিক্ত তথ্য ও চূড়ান্তকরণ</h2>
                <p>আপনার যদি আরও কিছু জানানোর বা জিজ্ঞাসা করার থাকে, তবে তা এখানে উল্লেখ করুন।</p>

                <div class="input-group">
                    <label for="additional_info"><i class="far fa-comment-dots label-icon"></i> এমন কোনো বিষয় আছে যা আপনি আমাদের জানাতে চান, যা এই ফর্মে উল্লেখ করা হয়নি কিন্তু আমাদের জানা গুরুত্বপূর্ণ?</label>
                    <textarea id="additional_info" name="অতিরিক্ত_তথ্য" placeholder="অন্যান্য প্রাসঙ্গিক তথ্য"></textarea>
                </div>
                <div class="input-group">
                    <label for="how_found_agency"><i class="fas fa-map-signs label-icon"></i> আমাদের এজেন্সি সম্পর্কে আপনি কীভাবে জানতে পারলেন?</label>
                    <input type="text" id="how_found_agency" name="এজেন্সি_সম্পর্কে_জানার_উৎস" placeholder="যেমন: রেফারেন্স, গুগল সার্চ, সোশ্যাল মিডিয়া ইত্যাদি">
                </div>
                 <div class="input-group">
                    <label for="questions_for_agency"><i class="fas fa-question-circle label-icon"></i> আমাদের কাছে আপনার কোনো প্রশ্ন আছে কি?</label>
                    <textarea id="questions_for_agency" name="এজেন্সির_কাছে_প্রশ্ন" placeholder="আপনার কোনো জিজ্ঞাসা থাকলে লিখুন"></textarea>
                </div>
                <div class="input-group">
                    <label for="submission_date_final"><i class="fas fa-calendar-day label-icon"></i> জমাদানের তারিখ:</label>
                    <input type="date" id="submission_date_final" name="স্বাক্ষরের_তারিখ" required>
                </div>
                <div class="input-group checkbox-group">
                    <label style="border: none; background: none; padding-left: 0;">
                        <input type="checkbox" id="terms_agreement" name="terms_agreement" required>
                        আমি নিশ্চিত করছি যে উপরে দেওয়া সকল তথ্য সঠিক এবং আমি এজেন্সির [শর্তাবলী/গোপনীয়তা নীতি]-এর সাথে একমত।
                    </label>
                </div>
            </div>

            <div class="form-navigation">
                <button type="button" class="prev-btn hidden"><i class="fas fa-arrow-left"></i> পূর্ববর্তী</button>
                <button type="button" class="next-btn">পরবর্তী <i class="fas fa-arrow-right"></i></button>
                <button type="submit" class="submit-btn hidden"><i class="fas fa-paper-plane"></i> ফরম জমা দিন</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const formSteps = Array.from(document.querySelectorAll('.form-step'));
            const progressSteps = Array.from(document.querySelectorAll('.progress-bar .step'));
            const mobileProgressInfo = document.querySelector('.mobile-progress-info'); // Mobile progress text display
            const nextBtn = document.querySelector('.next-btn');
            const prevBtn = document.querySelector('.prev-btn');
            const submitBtn = document.querySelector('.submit-btn');
            const form = document.getElementById('clientOnboardingForm');

            let currentStep = 0;

            function displayError(field, message) {
                field.classList.add('invalid');
                let errorMsgContainer = field.closest('.input-group'); // Find the parent input-group
                 if (!errorMsgContainer && (field.type === 'radio' || field.type === 'checkbox')) {
                    errorMsgContainer = field.closest('.radio-group, .checkbox-group').parentNode;
                }
                if (!errorMsgContainer) errorMsgContainer = field.parentNode; // Fallback

                let errorMsg = errorMsgContainer.querySelector('.error-message');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'error-message';
                    errorMsgContainer.appendChild(errorMsg);
                }
                errorMsg.textContent = message;
            }

            function clearError(field) {
                field.classList.remove('invalid');
                 let errorMsgContainer = field.closest('.input-group');
                 if (!errorMsgContainer && (field.type === 'radio' || field.type === 'checkbox')) {
                    errorMsgContainer = field.closest('.radio-group, .checkbox-group').parentNode;
                }
                 if (!errorMsgContainer) errorMsgContainer = field.parentNode;

                let errorMsg = errorMsgContainer.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
            
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            function isValidPhoneNumber(phone) {
                const phoneRegex = /^[+]?[\d\s\-\(\)]{7,20}$/; 
                return phoneRegex.test(phone);
            }
            
            function isValidMobileNumberBD(mobile) {
                const mobileRegex = /^01[3-9]\d{8}$/; 
                return mobileRegex.test(mobile) && mobile.length === 11;
            }

            function validateStep(stepIndex) {
                let isValid = true;
                const currentStepElement = formSteps[stepIndex];
                currentStepElement.querySelectorAll('.input-group .error-message').forEach(msg => msg.remove()); // Clear previous general errors in step
                currentStepElement.querySelectorAll('.invalid').forEach(el => el.classList.remove('invalid'));

                // More specific selector for fields to validate within the current step
                const fieldsToValidate = currentStepElement.querySelectorAll('.input-group input[required], .input-group textarea[required], .input-group select[required], .input-group input[type="email"], .input-group input[type="tel"]');
                
                fieldsToValidate.forEach(field => {
                    // Check if field is visible (not inside a hidden conditional-field)
                    let isVisible = true;
                    let parentConditional = field.closest('.conditional-field');
                    if (parentConditional && parentConditional.style.display === 'none') {
                        isVisible = false;
                    }

                    if (isVisible) { // Only validate visible fields
                        let fieldValid = true;
                        let errorMessage = '';

                        if (field.hasAttribute('required')) {
                            if (field.type === 'checkbox') {
                                if (!field.checked) {
                                    fieldValid = false;
                                    errorMessage = 'এই ফিল্ডটি আবশ্যক।';
                                }
                            } else if (field.type === 'radio') {
                                const radioGroup = currentStepElement.querySelectorAll(`input[name="${field.name}"]`);
                                if (!Array.from(radioGroup).some(radio => radio.checked)) {
                                    fieldValid = false;
                                    errorMessage = 'অনুগ্রহ করে একটি অপশন নির্বাচন করুন।';
                                }
                            } else if (!field.value.trim()) {
                                fieldValid = false;
                                errorMessage = 'অনুগ্রহ করে এই ফিল্ডটি পূরণ করুন।';
                            }
                        }

                        if (fieldValid && field.type === 'email' && field.value.trim() !== '') {
                            if (!isValidEmail(field.value)) {
                                fieldValid = false;
                                errorMessage = 'সঠিক ইমেইল ফরম্যাট দিন (যেমন: user@example.com)।';
                            }
                        }
                        
                        if (fieldValid && field.id === 'contact_phone' && field.value.trim() !== '') {
                             if (!isValidPhoneNumber(field.value)) {
                                fieldValid = false;
                                errorMessage = 'সঠিক ফোন নম্বর দিন।';
                            }
                        }
                        if (fieldValid && field.id === 'contact_alternative_phone' && field.value.trim() !== '') {
                             if (!isValidPhoneNumber(field.value)) { 
                                fieldValid = false;
                                errorMessage = 'সঠিক ফোন নম্বর দিন।';
                            }
                        }
                         if (fieldValid && field.id === 'whatsapp_number' && field.value.trim() !== '') {
                            if (!isValidMobileNumberBD(field.value)) {
                                fieldValid = false;
                                errorMessage = 'সঠিক ১১ সংখ্যার WhatsApp নম্বর দিন (যেমন: 01xxxxxxxxx)।';
                            }
                        }

                        if (!fieldValid) {
                            isValid = false;
                            displayError(field, errorMessage);
                        } else {
                            clearError(field);
                        }
                    }
                });
                return isValid;
            }
            
            function updateFormUI() {
                formSteps.forEach((step, index) => {
                    step.classList.toggle('active-step', index === currentStep);
                });

                progressSteps.forEach((stepLi, index) => {
                    stepLi.classList.remove('active', 'completed');
                    const stepTextSpan = stepLi.querySelector('.step-text');
                    if (index === currentStep) {
                        stepLi.classList.add('active');
                        if (stepTextSpan) stepTextSpan.style.display = 'inline'; // Show text for active step on desktop
                    } else if (index < currentStep) {
                        stepLi.classList.add('completed');
                         if (stepTextSpan) stepTextSpan.style.display = 'inline'; // Show text for completed steps on desktop
                    } else {
                        if (stepTextSpan && window.innerWidth <= 768) { // Hide text for non-active/non-completed on tablet/mobile
                             stepTextSpan.style.display = 'none';
                        } else if (stepTextSpan) {
                            stepTextSpan.style.display = 'inline';
                        }
                    }
                });
                
                // Update mobile progress info
                if (mobileProgressInfo) {
                    const currentStepTitle = progressSteps[currentStep].dataset.stepTitle || `ধাপ ${currentStep + 1}`;
                    mobileProgressInfo.textContent = `ধাপ ${currentStep + 1} / ${formSteps.length}: ${currentStepTitle}`;
                }


                prevBtn.classList.toggle('hidden', currentStep === 0);
                nextBtn.classList.toggle('hidden', currentStep === formSteps.length - 1);
                submitBtn.classList.toggle('hidden', currentStep !== formSteps.length - 1);
            }
            
            function setDateFields() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                const formattedDate = `${year}-${month}-${day}`;
                
                const submissionDateFinalInput = document.getElementById('submission_date_final');
                if (submissionDateFinalInput) submissionDateFinalInput.value = formattedDate;
            }

            setDateFields();

            nextBtn.addEventListener('click', () => {
                if (validateStep(currentStep)) {
                    if (currentStep < formSteps.length - 1) {
                        currentStep++;
                        updateFormUI();
                         window.scrollTo(0, 0);
                    }
                } else {
                    const firstInvalidField = formSteps[currentStep].querySelector('.invalid');
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                         firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentStep > 0) {
                    currentStep--;
                    updateFormUI();
                    window.scrollTo(0, 0);
                }
            });
            
            progressSteps.forEach(stepLi => {
                stepLi.addEventListener('click', (e) => {
                    const targetStep = parseInt(e.currentTarget.dataset.step) - 1;
                    
                    if (targetStep < currentStep) {
                         currentStep = targetStep;
                         updateFormUI();
                         window.scrollTo(0, 0);
                    } 
                    else if (targetStep > currentStep) {
                        let canJump = true;
                        for (let i = currentStep; i < targetStep; i++) {
                             if (!validateStep(i)) {
                                canJump = false;
                                currentStep = i; 
                                updateFormUI();
                                window.scrollTo(0, 0);
                                const firstInvalidField = formSteps[i].querySelector('.invalid');
                                if (firstInvalidField) {
                                    firstInvalidField.focus();
                                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                                break;
                             }
                        }
                        if (canJump) {
                            currentStep = targetStep;
                            updateFormUI();
                            window.scrollTo(0, 0);
                        }
                    }
                });
            });

            form.addEventListener('submit', async (e) => {
                e.preventDefault(); 
                if (!validateStep(currentStep)) {
                    const firstInvalidField = formSteps[currentStep].querySelector('.invalid');
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    return;
                }

                    const formData = new FormData(form);
                    const data = {};
                    formData.forEach((value, key) => {
                        if (key.endsWith('[]')) { 
                            const actualKey = key.slice(0, -2);
                        if (!data[actualKey]) data[actualKey] = [];
                            data[actualKey].push(value);
                        } else {
                           data[key] = value;
                        }
                    });

                try {
                    const res = await fetch('submit.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });
                    const json = await res.json();
                    if (json.success) {
                        alert('সাবমিশন সম্পন্ন! সাবমিশন আইডি: ' + json.submission_id);
                        form.reset();
                        currentStep = 0;
                        setDateFields();
                        updateFormUI();
                        window.scrollTo(0, 0);
                } else {
                        alert('সেভ ব্যর্থ: ' + (json.message || 'Unknown error'));
                        console.error(json);
                    }
                } catch (err) {
                    alert('নেটওয়ার্ক/সার্ভার ত্রুটি। পরে চেষ্টা করুন।');
                    console.error(err);
                }
            });
            
            // Initial UI update for responsive progress bar text handling
            window.addEventListener('resize', updateFormUI);
            updateFormUI(); // Initialize
        });

        function toggleConditionalField(fieldId, show) {
            const field = document.getElementById(fieldId);
            if (field) {
                field.style.display = show ? 'block' : 'none';
                const inputs = field.querySelectorAll('input, textarea, select');
                if (!show) {
                    inputs.forEach(input => {
                        if (input.type === 'radio' || input.type === 'checkbox') input.checked = false;
                        else input.value = '';
                        input.classList.remove('invalid'); // Clear validation state
                        let errorMsg = input.parentNode.querySelector('.error-message');
                        if(errorMsg) errorMsg.remove();
                    });
                }
            }
        }

        function addGoalField() {
            const container = document.getElementById('goals_container');
            const newGoalGroup = document.createElement('div');
            newGoalGroup.className = 'dynamic-item-group';
            newGoalGroup.innerHTML = '<input type="text" name="লক্ষ্য[]" placeholder="আরেকটি লক্ষ্য">';
            container.appendChild(newGoalGroup);
        }

        function addCompetitorField() {
            const container = document.getElementById('competitors_container');
            const newCompetitorGroup = document.createElement('div');
            newCompetitorGroup.className = 'dynamic-item-group';
            newCompetitorGroup.innerHTML = `
                <input type="text" name="প্রতিযোগী_নাম[]" placeholder="প্রতিযোগীর নাম" style="margin-bottom: 5px;">
                <input type="url" name="প্রতিযোগী_ওয়েবসাইট[]" placeholder="ওয়েবসাইট (https://example.com)">
            `;
            container.appendChild(newCompetitorGroup);
        }
    </script>
</body>
</html>
