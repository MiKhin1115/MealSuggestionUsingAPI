@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <!-- Breadcrumbs -->
    <div class="flex items-center text-sm text-gray-600 mb-8">
        <a href="{{ route('home') }}" class="hover:text-green-600">Home</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('articles.index') }}" class="hover:text-green-600">Articles</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-800">
            @if($slug == 'lower-vs-higher-calories')
                Is Lower Calories Better Than Higher Calories?
            @elseif($slug == 'understanding-macronutrients')
                Understanding Macronutrients
            @elseif($slug == 'meal-prep-101')
                Meal Prep 101: Save Time & Eat Well
            @elseif($slug == 'mediterranean-diet-explained')
                The Mediterranean Diet Explained
            @elseif($slug == 'superfoods-facts-vs-fiction')
                Superfoods: Facts vs. Fiction
            @elseif($slug == 'practice-of-mindful-eating')
                The Practice of Mindful Eating
            @elseif($slug == 'healthiest-cooking-methods')
                Healthiest Cooking Methods Explained
            @else
                Article Details
            @endif
        </span>
    </div>

    <div class="max-w-4xl mx-auto">
        <!-- Article Header Section -->
        <div class="mb-10">
            @if($slug == 'lower-vs-higher-calories')
                <span class="inline-block py-1 px-3 rounded-full bg-green-100 text-green-800 text-sm font-medium mb-4">Nutrition</span>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-6">Is Lower Calories Better Than Higher Calories?</h1>
                <p class="text-xl text-gray-600 mb-6">Exploring the nuances of caloric intake and why the quality of calories matters as much as the quantity.</p>
                
                <div class="flex items-center mb-6">
                    <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Author" class="w-12 h-12 rounded-full">
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Dr. Sarah Chen</p>
                        <p class="text-sm text-gray-500">Nutritionist & Health Researcher</p>
                    </div>
                    <div class="ml-auto flex items-center text-sm text-gray-500">
                        <i class="far fa-clock mr-1"></i> 7 min read
                        <span class="mx-2">•</span>
                        <i class="far fa-calendar-alt mr-1"></i> June 12, 2023
                    </div>
                </div>
                
                <img src="https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80" 
                     alt="Low vs High Calorie Diets" class="w-full h-96 object-cover rounded-xl">
            @elseif($slug == 'understanding-macronutrients')
                <!-- Template for other articles -->
                <span class="inline-block py-1 px-3 rounded-full bg-green-100 text-green-800 text-sm font-medium mb-4">Nutrition</span>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-6">Understanding Macronutrients</h1>
                <p class="text-xl text-gray-600 mb-6">Learn how proteins, carbohydrates, and fats fuel your body and why balanced intake matters.</p>
                
                <div class="flex items-center mb-6">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Author" class="w-12 h-12 rounded-full">
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Dr. Mark Johnson</p>
                        <p class="text-sm text-gray-500">Sports Nutritionist</p>
                    </div>
                    <div class="ml-auto flex items-center text-sm text-gray-500">
                        <i class="far fa-clock mr-1"></i> 5 min read
                        <span class="mx-2">•</span>
                        <i class="far fa-calendar-alt mr-1"></i> May 18, 2023
                    </div>
                </div>
                
                <img src="https://images.unsplash.com/photo-1505253758473-96b7015fcd40?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80" 
                     alt="Understanding Macronutrients" class="w-full h-96 object-cover rounded-xl">
            @else
                <!-- Default template for other articles -->
                <span class="inline-block py-1 px-3 rounded-full bg-green-100 text-green-800 text-sm font-medium mb-4">Article</span>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-6">{{ str_replace('-', ' ', ucwords($slug)) }}</h1>
                <p class="text-xl text-gray-600 mb-6">Detailed information about this topic.</p>
                
                <div class="flex items-center mb-6">
                    <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Author" class="w-12 h-12 rounded-full">
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Author Name</p>
                        <p class="text-sm text-gray-500">Health Expert</p>
                    </div>
                    <div class="ml-auto flex items-center text-sm text-gray-500">
                        <i class="far fa-clock mr-1"></i> 5 min read
                        <span class="mx-2">•</span>
                        <i class="far fa-calendar-alt mr-1"></i> June 1, 2023
                    </div>
                </div>
                
                <img src="https://images.unsplash.com/photo-1576402187878-974f70c890a5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80" 
                     alt="Article" class="w-full h-96 object-cover rounded-xl">
            @endif
        </div>
        
        <!-- Article Content -->
        <div class="prose prose-lg max-w-none mb-10">
            @if($slug == 'lower-vs-higher-calories')
                <h2>The Calorie Myth: Quantity vs. Quality</h2>
                
                <p>When it comes to nutrition and weight management, calories often take center stage. The conventional wisdom suggests that consuming fewer calories than you burn leads to weight loss, while consuming more leads to weight gain. While this energy balance equation is fundamentally true, the reality of how our bodies process calories is far more complex.</p>
                
                <p>The question "Is lower calories better than higher calories?" isn't as straightforward as it might seem. In this article, we'll explore the nuances of caloric intake and why the quality of your calories matters as much as—if not more than—the quantity.</p>
                
                <h3>Understanding Calories: More Than Just Numbers</h3>
                
                <p>A calorie is a unit of energy. Specifically, it's the amount of energy needed to raise the temperature of 1 gram of water by 1 degree Celsius. In nutrition, we typically use kilocalories (kcal), which equal 1,000 calories. When you see "calories" on food labels, they're actually referring to kilocalories.</p>
                
                <p>But not all calories are created equal. Your body processes 100 calories from broccoli differently than 100 calories from a cookie. This is because foods contain various combinations of macronutrients (proteins, carbohydrates, and fats) and micronutrients (vitamins and minerals), as well as fiber, water, and other compounds that affect how they're digested and metabolized.</p>
                
                <h3>The Case for Lower Calorie Intake</h3>
                
                <p>There are legitimate reasons why consuming fewer calories might be beneficial in certain situations:</p>
                
                <ul>
                    <li><strong>Weight management:</strong> If your goal is weight loss, creating a calorie deficit by consuming fewer calories than you burn is essential.</li>
                    <li><strong>Longevity research:</strong> Some studies suggest that caloric restriction may extend lifespan and reduce the risk of age-related diseases, though more research is needed in humans.</li>
                    <li><strong>Metabolic health:</strong> For individuals with certain metabolic conditions, moderating calorie intake can help manage blood sugar levels and reduce stress on the body's systems.</li>
                </ul>
                
                <h3>When Higher Calorie Intake Is Beneficial</h3>
                
                <p>Despite the popularity of calorie-restricted diets, there are many scenarios where consuming more calories is not only acceptable but necessary:</p>
                
                <ul>
                    <li><strong>Growing children and teenagers:</strong> During periods of growth and development, higher calorie needs are essential to support bone and muscle formation, brain development, and overall growth.</li>
                    <li><strong>Athletes and active individuals:</strong> Those who engage in regular intense physical activity require more energy to fuel their workouts, recover properly, and maintain or build muscle mass.</li>
                    <li><strong>Pregnancy and breastfeeding:</strong> Women need additional calories during these periods to support the development and nourishment of their babies.</li>
                    <li><strong>Recovery from illness or injury:</strong> The body often requires extra energy to heal and repair tissues during recovery.</li>
                </ul>
                
                <div class="bg-blue-50 p-6 rounded-xl my-8">
                    <h4 class="text-xl font-bold text-blue-800 mb-4">Calorie Quality Checklist</h4>
                    <p class="text-blue-700 mb-4">When evaluating your food choices, consider these factors beyond just calorie count:</p>
                    <ul class="text-blue-700">
                        <li><strong>Nutrient density:</strong> How many vitamins, minerals, and beneficial compounds does the food provide relative to its calories?</li>
                        <li><strong>Fiber content:</strong> Does the food contain fiber that promotes fullness and digestive health?</li>
                        <li><strong>Protein quality:</strong> Does it provide complete proteins or essential amino acids?</li>
                        <li><strong>Fat composition:</strong> Does it contain healthy fats like omega-3s rather than trans fats?</li>
                        <li><strong>Processing level:</strong> Is it a whole food or highly processed?</li>
                    </ul>
                </div>
                
                <h3>Quality Over Quantity: The Nutrient Density Approach</h3>
                
                <p>Rather than focusing solely on calorie counts, nutrition experts increasingly recommend emphasizing nutrient density—the amount of beneficial nutrients a food provides relative to its energy content.</p>
                
                <p>For example, both an apple and a small serving of candy might contain about 100 calories, but the apple provides fiber, vitamins, minerals, and phytonutrients, while the candy offers little beyond sugar and perhaps some fat.</p>
                
                <p>By choosing nutrient-dense foods, you can:</p>
                
                <ul>
                    <li>Feel more satisfied with fewer calories</li>
                    <li>Ensure your body gets the nutrients it needs to function optimally</li>
                    <li>Reduce inflammation and oxidative stress</li>
                    <li>Support your immune system</li>
                    <li>Promote long-term health</li>
                </ul>
                
                <h3>The Metabolic Impact of Different Foods</h3>
                
                <p>The thermic effect of food (TEF) refers to the energy your body uses to digest, absorb, and process nutrients. This varies significantly between macronutrients:</p>
                
                <ul>
                    <li><strong>Protein:</strong> 20-30% of calories consumed</li>
                    <li><strong>Carbohydrates:</strong> 5-10% of calories consumed</li>
                    <li><strong>Fats:</strong> 0-3% of calories consumed</li>
                </ul>
                
                <p>This means that 100 calories from protein might result in a net gain of only 70-80 calories, while 100 calories from fat could provide 97-100 usable calories. This is one reason why higher-protein diets are often effective for weight management—they naturally increase calorie expenditure through digestion.</p>
                
                <h3>Finding Your Personal Calorie Balance</h3>
                
                <p>There's no one-size-fits-all answer to how many calories you should consume. Your optimal intake depends on numerous factors, including:</p>
                
                <ul>
                    <li>Age and gender</li>
                    <li>Height and weight</li>
                    <li>Body composition (ratio of muscle to fat)</li>
                    <li>Activity level and exercise routine</li>
                    <li>Metabolic health and medical conditions</li>
                    <li>Personal goals (weight maintenance, loss, or gain; athletic performance; etc.)</li>
                </ul>
                
                <p>Working with a registered dietitian or nutritionist can help you determine an appropriate calorie range that supports your health and goals.</p>
                
                <div class="bg-green-50 p-6 rounded-xl my-8">
                    <h4 class="text-xl font-bold text-green-800 mb-4">Key Takeaways</h4>
                    <ul class="text-green-700">
                        <li>The quality of calories matters as much as the quantity</li>
                        <li>Lower calorie intake is beneficial for weight loss and may have longevity benefits</li>
                        <li>Higher calorie needs are normal during growth, for athletes, during pregnancy, and for recovery</li>
                        <li>Focus on nutrient density rather than calorie counting alone</li>
                        <li>Different macronutrients have varying effects on metabolism and satiety</li>
                        <li>Your optimal calorie intake is personal and depends on multiple factors</li>
                    </ul>
                </div>
                
                <h3>Conclusion: A Balanced Perspective on Calories</h3>
                
                <p>So, is lower calories better than higher calories? The answer is: it depends on your individual circumstances, health status, and goals.</p>
                
                <p>Rather than fixating on calorie numbers, a more sustainable and health-promoting approach is to:</p>
                
                <ol>
                    <li>Focus on whole, minimally processed foods that provide abundant nutrients</li>
                    <li>Listen to your body's hunger and fullness cues</li>
                    <li>Adjust your intake based on your current life stage, activity level, and health needs</li>
                    <li>Consider how foods make you feel physically and mentally</li>
                    <li>Maintain a positive relationship with food rather than viewing it solely through the lens of calories</li>
                </ol>
                
                <p>By taking this more nuanced approach to nutrition, you can develop eating habits that support both your immediate goals and your long-term health, without unnecessary restriction or fear around calorie intake.</p>
                
            @elseif($slug == 'understanding-macronutrients')
                <p>Content for the Understanding Macronutrients article would go here.</p>
                <p>This article would explain proteins, carbohydrates, and fats in detail.</p>
            @else
                <p>Content for this article hasn't been written yet. Check back soon!</p>
            @endif
        </div>
        
        <!-- Author Bio -->
        @if($slug == 'lower-vs-higher-calories')
        <div class="bg-gray-50 p-6 rounded-xl mb-10">
            <div class="flex items-start">
                <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Author" class="w-16 h-16 rounded-full">
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-gray-900">About Dr. Sarah Chen</h3>
                    <p class="text-gray-700 mt-2">Dr. Sarah Chen is a registered dietitian and nutritional researcher with over 15 years of experience in the field. She holds a Ph.D. in Nutritional Sciences from Stanford University and specializes in metabolic health and personalized nutrition approaches. Dr. Chen regularly contributes to peer-reviewed journals and is passionate about translating complex nutritional science into practical advice.</p>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Related Articles -->
        <div class="mb-10">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Related Articles</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('articles.show', 'understanding-macronutrients') }}" class="group">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                        <img src="https://images.unsplash.com/photo-1505253758473-96b7015fcd40?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" 
                             alt="Understanding Macronutrients" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-green-600">Understanding Macronutrients</h4>
                            <p class="text-gray-600 text-sm mt-1">Learn how proteins, carbohydrates, and fats fuel your body.</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('articles.show', 'mediterranean-diet-explained') }}" class="group">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                        <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" 
                             alt="Mediterranean Diet" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-green-600">The Mediterranean Diet Explained</h4>
                            <p class="text-gray-600 text-sm mt-1">Explore one of the healthiest eating patterns in the world.</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('articles.show', 'superfoods-facts-vs-fiction') }}" class="group">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                        <img src="https://images.unsplash.com/photo-1576402187878-974f70c890a5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" 
                             alt="Superfoods" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-green-600">Superfoods: Facts vs. Fiction</h4>
                            <p class="text-gray-600 text-sm mt-1">Separating hype from nutritional reality about trendy foods.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Comments Section -->
        <div class="mb-10">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Comments (3)</h3>
            <div class="space-y-6">
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <div class="flex items-start mb-4">
                        <img src="https://randomuser.me/api/portraits/men/72.jpg" alt="Commenter" class="w-10 h-10 rounded-full">
                        <div class="ml-4">
                            <h5 class="text-md font-bold text-gray-900">Michael Thompson</h5>
                            <p class="text-sm text-gray-500">Posted 2 days ago</p>
                        </div>
                    </div>
                    <p class="text-gray-700">Great article! I've been focusing too much on calorie counting without considering nutrient density. This gave me a new perspective on how to approach my diet.</p>
                    <div class="mt-4 flex items-center">
                        <button class="flex items-center text-gray-500 hover:text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                            12
                        </button>
                        <button class="flex items-center text-gray-500 hover:text-green-600 ml-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />
                            </svg>
                            2
                        </button>
                        <button class="text-gray-500 hover:text-green-600 ml-4">Reply</button>
                    </div>
                </div>
                
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <div class="flex items-start mb-4">
                        <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Commenter" class="w-10 h-10 rounded-full">
                        <div class="ml-4">
                            <h5 class="text-md font-bold text-gray-900">Laura Martinez</h5>
                            <p class="text-sm text-gray-500">Posted 3 days ago</p>
                        </div>
                    </div>
                    <p class="text-gray-700">As a personal trainer, I'm always telling my clients that it's not just about eating less, but eating better. This article explains that concept perfectly. I'll be sharing this with my clients!</p>
                    <div class="mt-4 flex items-center">
                        <button class="flex items-center text-gray-500 hover:text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                            8
                        </button>
                        <button class="flex items-center text-gray-500 hover:text-green-600 ml-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />
                            </svg>
                            0
                        </button>
                        <button class="text-gray-500 hover:text-green-600 ml-4">Reply</button>
                    </div>
                </div>
                
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <div class="flex items-start mb-4">
                        <img src="https://randomuser.me/api/portraits/men/42.jpg" alt="Commenter" class="w-10 h-10 rounded-full">
                        <div class="ml-4">
                            <h5 class="text-md font-bold text-gray-900">David Wilson</h5>
                            <p class="text-sm text-gray-500">Posted 4 days ago</p>
                        </div>
                    </div>
                    <p class="text-gray-700">I appreciated the balanced perspective here. As someone who has been active in sports all my life, I've seen how both under-eating and over-eating can negatively impact performance. The focus on nutrient quality makes so much sense.</p>
                    <div class="mt-4 flex items-center">
                        <button class="flex items-center text-gray-500 hover:text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                            5
                        </button>
                        <button class="flex items-center text-gray-500 hover:text-green-600 ml-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />
                            </svg>
                            1
                        </button>
                        <button class="text-gray-500 hover:text-green-600 ml-4">Reply</button>
                    </div>
                </div>
            </div>
            
            <!-- Comment Form -->
            <div class="mt-8">
                <h4 class="text-xl font-bold text-gray-900 mb-4">Leave a Comment</h4>
                <form>
                    <div class="mb-4">
                        <textarea rows="4" class="w-full px-4 py-3 rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Share your thoughts..."></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <input type="text" class="px-4 py-3 rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Your Name">
                        <input type="email" class="px-4 py-3 rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Your Email">
                    </div>
                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="save-info" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <label for="save-info" class="ml-2 text-gray-700">Save my name and email for the next time I comment</label>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition-colors">
                        Post Comment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 