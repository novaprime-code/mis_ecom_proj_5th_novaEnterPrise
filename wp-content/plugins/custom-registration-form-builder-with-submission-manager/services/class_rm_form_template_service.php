<?php

class RM_form_template_service{
	
    public function create_contact_template($form_id, $form_type, $template){
        if($template=='c0'){
            $this->create_contact_template_c0($form_id);
        }elseif($template=='c1'){
            $this->create_contact_template_c1($form_id);
        }elseif($template=='c2'){
            $this->create_contact_template_c2($form_id);
        }elseif($template=='c3'){
            $this->create_contact_template_c3($form_id);
        }elseif($template=='c4'){
            $this->create_contact_template_c4($form_id);
        }elseif($template=='c5'){
            $this->create_contact_template_c5($form_id);
        }elseif($template=='c6'){
            $this->create_contact_template_c6($form_id);
        }elseif($template=='c7'){
            $this->create_contact_template_c7($form_id);
        }elseif($template=='c8'){
            $this->create_contact_template_c8($form_id);
        }elseif($template=='c9'){
            $this->create_contact_template_c9($form_id);
        }elseif($template=='c10'){
            $this->create_contact_template_c10($form_id);
        }elseif($template=='c11'){
            $this->create_contact_template_c11($form_id);
        }elseif($template=='c12'){
            $this->create_contact_template_c12($form_id);
        }elseif($template=='c13'){
            $this->create_contact_template_c13($form_id);
        }elseif($template=='c14'){
            $this->create_contact_template_c14($form_id);
        }elseif($template=='c15'){
            $this->create_contact_template_c15($form_id);
        }elseif($template=='c16'){
            $this->create_contact_template_c16($form_id);
        }elseif($template=='c17'){
            $this->create_contact_template_c17($form_id);
        }elseif($template=='c18'){
            $this->create_contact_template_c18($form_id);
        }elseif($template=='c19'){
            $this->create_contact_template_c19($form_id);
        }elseif($template=='cp1') {
            $this->create_contact_template_cp1($form_id);
        }elseif($template=='cp2'){
            $this->create_contact_template_cp2($form_id);
        }elseif($template=='cp3'){
            $this->create_contact_template_cp3($form_id);
        }elseif($template=='cp4'){
            $this->create_contact_template_cp4($form_id);
        }
    }
    public function create_registration_template($form_id, $form_type, $template){
        if($template=='r0'){
            $this->create_registration_template_r0($form_id);
        }elseif($template=='r1'){
            $this->create_registration_template_r1($form_id);
        }elseif($template=='r2'){
            $this->create_registration_template_r2($form_id);
        }elseif($template=='r3'){
            $this->create_registration_template_r3($form_id);
        }elseif($template=='rp1'){
            $this->create_registration_template_rp1($form_id);
        }

    }
    public function create_contact_template_c0($form_id){
        //Email 
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', 'Enter Email address', 1);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
    }
    public function create_contact_template_c1($form_id){
        // Name ($form_id, $page_no=1, $label='Name', $placeholder='', $required=0, $order=1)
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Smart Registration Contact Form", '<h1 style="color: #5e9ca0"><span style="color: #003300">User registration and Contact Form Together!&nbsp;</span></h1>
<h2 style="color: #2e6c80">&nbsp;</h2>
<h2 style="color: #2e6c80">Why use several forms when one form can do it all?&nbsp;</h2>
<p>&nbsp;</p>
<h2><span style="color: #333300">Highlights:</span></h2>
<ul style="list-style-type: square">
<li>Enquires will be stored in RegistraitonMagics INBOX in addition to being sent to Email</li>
<li>Premium feature includes an option to include a User history, such as previous submissions, and purchase history, along with user inquiry.</li>
<li>When logged in, users will see fields filled in automatically (good for returning users).</li>
<li>WordPress account creation can be turned on for users if required.</li>
</ul>
<p>&nbsp;</p>
<h2><span style="color: #333300">Features:</span></h2>
<ol style="font-size: 14px;line-height: 32px;font-weight: bold">
<li style="clear: both">User Registration Form</li>
<li style="clear: both">Easy to edit as per your needs</li>
<li style="clear: both">Keep track of users trying to get in touch with you</li>
<li style="clear: both">Easily export user information&nbsp;</li>
<li style="clear: both">Receive email notification&nbsp;</li>
<li style="clear: both">Contact form</li>
</ol>
<p>&nbsp;</p>',"",1);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",2);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        
        $field_mood_id = $this->create_template_select_field($form_id, 1, 'What is your emotional state?', '', array('Happy', 'Excited', 'Sad','Confused','Upset'), 3);
        $this->create_template_row_structure($form_id, array($field_mood_id), 1, '1');
        
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', 'Enter First Name', '',1, 4);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', 'Enter Last Name', '',1, 5);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');

        //Email 
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', 'Enter Email address', 6);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
        
        $field_msg_type_id = $this->create_template_select_field($form_id, 1, 'Message Type', '', array('Pre-sales', 'Post-sales', 'Request','Feedback','Partnership', 'Other'), 7);
        $this->create_template_row_structure($form_id, array($field_msg_type_id), 1, '1');
        
        $field_subject_id = $this->create_template_text_field($form_id, 1, 'Subject', 'What is it regarding?', '',1, 8);
        $this->create_template_row_structure($form_id, array($field_subject_id), 1, '1:1');
	// Message
        $field_msg_id = $this->create_template_textarea_field($form_id, 1, 'Comment or Message', 'Enter Your Message', '', 1, 9, 4, 12);
        $this->create_template_row_structure($form_id, array($field_msg_id), 1, '1');

    }
    public function create_contact_template_c2($form_id){
        //description
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "rich text", "<p>Note for Admin (Delete before publishing)</p>
<p>1. Enquires will be stored inside RegistraitonMagic's INBOX in addition to being sent via Email.</p>
<p>2. Premium features include an option to append user history, such as previous submissions, and purchases to the form data.</p>
<p>3. Logged in users will see fields filled in automatically (good for returning users).</p>
<p>4. WordPress account creation on form submission can be turned on for users if required. </p>
<p>5. Please replace the placeholder Terms and Conditions according to your needs.</p>","",1);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        //divider
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",2);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        // Name
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', 'Enter First Name', '',1, 3);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', 'Enter Last Name', '',1, 4);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');

        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', 'Enter Email address', 5);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
        
        //Message Type
        $field_msg_type_id = $this->create_template_select_field($form_id, 1, 'Suggestions regarding', '', array("Pre-sales", "Post-sales", "Request", "Feedback", "Partnership", "Other"), 6);
        $this->create_template_row_structure($form_id, array($field_msg_type_id), 1, '1');
        
        //Subject
        $field_subject_id = $this->create_template_text_field($form_id, 1, 'Subject', 'What is it regarding?', '',1, 7);
        $this->create_template_row_structure($form_id, array($field_subject_id), 1, '1');
        
        //Message
        $field_msg_id = $this->create_template_textarea_field($form_id, 1, 'Comment or Message', 'Enter Your Message', '', 1, 8, 4, 12);
        $this->create_template_row_structure($form_id, array($field_msg_id), 1, '1');

        //Terms & Conditions
        $field_terms_id = $this->create_template_termscondition_field($form_id, 1, $label='Terms & Conditions', '', 9, 'TERMS AND CONDITIONS
Last updated April 02, 2022
TABLE OF CONTENTS
1. AGREEMENT TO TERMS
2. INTELLECTUAL PROPERTY RIGHTS
3. USER REPRESENTATIONS
4. USER REGISTRATION
5. PROHIBITED ACTIVITIES
6. USER GENERATED CONTRIBUTIONS
7. CONTRIBUTION LICENSE
8. SOCIAL MEDIA
9. SUBMISSIONS
10. SITE MANAGEMENT
11. COPYRIGHT INFRINGEMENTS
12. TERM AND TERMINATION
13. MODIFICATIONS AND INTERRUPTIONS
14. GOVERNING LAW
15. DISPUTE RESOLUTION
16. CORRECTIONS
17. DISCLAIMER
18. LIMITATIONS OF LIABILITY
19. INDEMNIFICATION
20. USER DATA
21. ELECTRONIC COMMUNICATIONS, TRANSACTIONS, AND SIGNATURES 22. CALIFORNIA USERS AND RESIDENTS
23. MISCELLANEOUS
24. CONTACT US
1. AGREEMENT TO TERMS
These Terms of Use constitute a legally binding agreement made between you, whether personally or on behalf of an entity (“you”) and RegistrationMagic ("Company," “we," “us," or “our”), concerning your access to and use of the https://registrationmagic.com website as well as any other media form, media channel, mobile website or mobile application related, linked, or otherwise connected thereto (collectively, the “Site”). We are registered in Canada and have our registered office at Toronto, Toronto, Ontario ZX4 WE2. Our VAT number is CAD000000. You agree that by accessing the Site, you have read, understood, and agreed to be bound by all of these Terms of Use. IF YOU DO NOT AGREE WITH ALL OF THESE TERMS OF USE, THEN YOU ARE EXPRESSLY PROHIBITED FROM USING THE SITE AND YOU MUST DISCONTINUE USE IMMEDIATELY.
Supplemental terms and conditions or documents that may be posted on the Site from time to time are hereby expressly incorporated herein by reference. We reserve the right, in our sole discretion, to make changes or modifications to these Terms of Use from time to time. We will alert you about any changes by updating the “Last updated” date of these Terms of Use, and you waive any right to receive specific notice of each such change. Please ensure that you check the applicable Terms every time you use our Site so that you understand which Terms apply. You will be subject to, and will be deemed to have been made aware of and to have accepted, the changes in any revised Terms of Use by your continued use of the Site after the date such revised Terms of Use are posted.
The information provided on the Site is not intended for distribution to or use by any person or entity in any jurisdiction or country where such distribution or use would be contrary to law or regulation or which would subject us to any registration requirement within such jurisdiction or country. Accordingly, those persons who choose to access the Site from other locations do so on their own initiative and are solely responsible for compliance with local laws, if and to the extent local laws are applicable.
The Site is not tailored to comply with industry-specific regulations (Health Insurance Portability and Accountability Act (HIPAA), Federal Information Security Management Act (FISMA), etc.), so if your interactions would be subjected to such laws, you may not use this Site. You may not use the Site in a way that would violate the Gramm- Leach-Bliley Act (GLBA).
The Site is intended for users who are at least 18 years old. Persons under the age of 18 are not permitted to use or register for the Site.
2. INTELLECTUAL PROPERTY RIGHTS
Unless otherwise indicated, the Site is our proprietary property and all source code, databases, functionality, software, website designs, audio, video, text, photographs, and graphics on the Site (collectively, the “Content”) and the trademarks, service marks, and logos contained therein (the “Marks”) are owned or controlled by us or licensed to us, and are protected by copyright and trademark laws and various other intellectual property rights and unfair competition laws of the United States, international copyright laws, and international conventions. The Content and the Marks are provided on the Site “AS IS” for your information and personal use only. Except as expressly provided in these Terms of Use, no part of the Site and no Content or Marks may be copied, reproduced, aggregated, republished, uploaded, posted, publicly displayed, encoded, translated, transmitted, distributed, sold, licensed, or otherwise exploited for any commercial purpose whatsoever, without our express prior written permission.
Provided that you are eligible to use the Site, you are granted a limited license to access and use the Site and to download or print a copy of any portion of the Content to which you have properly gained access solely for your personal, non-commercial use. We reserve all rights not expressly granted to you in and to the Site, the Content and the Marks.
3. USER REPRESENTATIONS
By using the Site, you represent and warrant that: (1) all registration information you submit will be true, accurate, current, and complete; (2) you will maintain the accuracy of such information and promptly update such registration information as necessary; (3) you have the legal capacity and you agree to comply with these Terms of Use; (4) you are not a minor in the jurisdiction in which you reside; (5) you will not access the Site through automated or non-human means, whether through a bot, script, or otherwise; (6) you will not use the Site for any illegal or unauthorized purpose; and (7) your use of the Site will not violate any applicable law or regulation.
If you provide any information that is untrue, inaccurate, not current, or incomplete, we have the right to suspend or terminate your account and refuse any and all current or future use of the Site (or any portion thereof).
4. USER REGISTRATION
You may be required to register with the Site. You agree to keep your password confidential and will be responsible for all use of your account and password. We reserve the right to remove, reclaim, or change a username you select if we determine, in our sole discretion, that such username is inappropriate, obscene, or otherwise objectionable.
5. PROHIBITED ACTIVITIES
You may not access or use the Site for any purpose other than that for which we make the Site available. The Site may not be used in connection with any commercial endeavors except those that are specifically endorsed or approved by us.

Systematically retrieve data or other content from the Site to create or compile, directly or indirectly, a collection, compilation, database, or directory without written permission from us.
Trick, defraud, or mislead us and other users, especially in any attempt to learn sensitive account information such as user passwords.
Circumvent, disable, or otherwise interfere with security-related features of the Site, including features that prevent or restrict the use or copying of any Content or enforce limitations on the use of the Site and/or the Content contained therein.
Disparage, tarnish, or otherwise harm, in our opinion, us and/or the Site.
Use any information obtained from the Site in order to harass, abuse, or harm another person.
Make improper use of our support services or submit false reports of abuse or misconduct.
Use the Site in a manner inconsistent with any applicable laws or regulations. Engage in unauthorized framing of or linking to the Site.
Upload or transmit (or attempt to upload or to transmit) viruses, Trojan horses, or other material, including excessive use of capital letters and spamming (continuous posting of repetitive text), that interferes with any party’s uninterrupted use and enjoyment of the Site or modifies, impairs, disrupts, alters, or interferes with the use, features, functions, operation, or maintenance of the Site.
Engage in any automated use of the system, such as using scripts to send comments or messages, or using any data mining, robots, or similar data gathering and extraction tools.
Delete the copyright or other proprietary rights notice from any Content. Attempt to impersonate another user or person or use the username of another user.
Upload or transmit (or attempt to upload or to transmit) any material that acts as a passive or active information collection or transmission mechanism, including without limitation, clear graphics interchange formats (“gifs”), 1×1 pixels, web bugs, cookies, or other similar devices (sometimes referred to as “spyware” or “passive collection mechanisms” or “pcms”).
Interfere with, disrupt, or create an undue burden on the Site or the networks or services connected to the Site.
Harass, annoy, intimidate, or threaten any of our employees or agents engaged in providing any portion of the Site to you.
Attempt to bypass any measures of the Site designed to prevent or restrict access to the Site, or any portion of the Site.
Copy or adapt the Site’s software, including but not limited to Flash, PHP, HTML, JavaScript, or other code.
Except as permitted by applicable law, decipher, decompile, disassemble, or reverse engineer any of the software comprising or in any way making up a part of the Site.
Except as may be the result of standard search engine or Internet browser usage, use, launch, develop, or distribute any automated system, including without limitation, any spider, robot, cheat utility, scraper, or offline reader that accesses the Site, or using or launching any unauthorized script or other software.
Use a buying agent or purchasing agent to make purchases on the Site.
Make any unauthorized use of the Site, including collecting usernames and/or email addresses of users by electronic or other means for the purpose of sending unsolicited email, or creating user accounts by automated means or under false pretenses.
Use the Site as part of any effort to compete with us or otherwise use the Site and/or the Content for any revenue-generating endeavor or commercial enterprise.
6. USER GENERATED CONTRIBUTIONS
The Site may invite you to chat, contribute to, or participate in blogs, message boards, online forums, and other functionality, and may provide you with the opportunity to create, submit, post, display, transmit, perform, publish, distribute, or broadcast content and materials to us or on the Site, including but not limited to text, writings, video, audio, photographs, graphics, comments, suggestions, or personal information or other material (collectively, "Contributions"). Contributions may be viewable by other users of the Site and through third-party websites. As such, any Contributions you transmit may be treated as non-confidential and non-proprietary. When you create or make available any Contributions, you thereby represent and warrant that:
The creation, distribution, transmission, public display, or performance, and the  accessing, downloading, or copying of your Contributions do not and will not

infringe the proprietary rights, including but not limited to the copyright, patent, trademark, trade secret, or moral rights of any third party.
You are the creator and owner of or have the necessary licenses, rights, consents, releases, and permissions to use and to authorize us, the Site, and other users of the Site to use your Contributions in any manner contemplated by the Site and these Terms of Use.
You have the written consent, release, and/or permission of each and every identifiable individual person in your Contributions to use the name or likeness of each and every such identifiable individual person to enable inclusion and use of your Contributions in any manner contemplated by the Site and these Terms of Use.
Your Contributions are not false, inaccurate, or misleading.
Your Contributions are not unsolicited or unauthorized advertising, promotional materials, pyramid schemes, chain letters, spam, mass mailings, or other forms of solicitation.
Your Contributions are not obscene, lewd, lascivious, filthy, violent, harassing, libelous, slanderous, or otherwise objectionable (as determined by us).
Your Contributions do not ridicule, mock, disparage, intimidate, or abuse anyone.
Your Contributions are not used to harass or threaten (in the legal sense of those terms) any other person and to promote violence against a specific person or class of people.
Your Contributions do not violate any applicable law, regulation, or rule.
Your Contributions do not violate the privacy or publicity rights of any third party.
Your Contributions do not violate any applicable law concerning child pornography, or otherwise intended to protect the health or well-being of minors.
Your Contributions do not include any offensive comments that are connected to race, national origin, gender, sexual preference, or physical handicap.
Your Contributions do not otherwise violate, or link to material that violates, any provision of these Terms of Use, or any applicable law or regulation.
Any use of the Site in violation of the foregoing violates these Terms of Use and may result in, among other things, termination or suspension of your rights to use the Site.
7. CONTRIBUTION LICENSE
By posting your Contributions to any part of the Site or making Contributions accessible to the Site by linking your account from the Site to any of your social networking accounts, you automatically grant, and you represent and warrant that you have the right to grant, to us an unrestricted, unlimited, irrevocable, perpetual, non-exclusive, transferable, royalty-free, fully-paid, worldwide right, and license to host, use, copy, reproduce, disclose, sell, resell, publish, broadcast, retitle, archive, store, cache, publicly perform, publicly display, reformat, translate, transmit, excerpt (in whole or in part), and distribute such Contributions (including, without limitation, your image and voice) for any purpose, commercial, advertising, or otherwise, and to prepare derivative works of, or incorporate into other works, such Contributions, and grant and authorize sublicenses of the foregoing. The use and distribution may occur in any media formats and through any media channels.
This license will apply to any form, media, or technology now known or hereafter developed, and includes our use of your name, company name, and franchise name, as applicable, and any of the trademarks, service marks, trade names, logos, and personal and commercial images you provide. You waive all moral rights in your Contributions, and you warrant that moral rights have not otherwise been asserted in your Contributions.
We do not assert any ownership over your Contributions. You retain full ownership of all of your Contributions and any intellectual property rights or other proprietary rights associated with your Contributions. We are not liable for any statements or representations in your Contributions provided by you in any area on the Site. You are solely responsible for your Contributions to the Site and you expressly agree to exonerate us from any and all responsibility and to refrain from any legal action against us regarding your Contributions.
We have the right, in our sole and absolute discretion, (1) to edit, redact, or otherwise change any Contributions; (2) to re-categorize any Contributions to place them in more appropriate locations on the Site; and (3) to pre-screen or delete any Contributions at any time and for any reason, without notice. We have no obligation to monitor your Contributions.

8. SOCIAL MEDIA
As part of the functionality of the Site, you may link your account with online accounts you have with third-party service providers (each such account, a “Third-Party Account”) by either: (1) providing your Third-Party Account login information through the Site; or (2) allowing us to access your Third-Party Account, as is permitted under the applicable terms and conditions that govern your use of each Third-Party Account. You represent and warrant that you are entitled to disclose your Third-Party Account login information to us and/or grant us access to your Third-Party Account, without breach by you of any of the terms and conditions that govern your use of the applicable Third-Party Account, and without obligating us to pay any fees or making us subject to any usage limitations imposed by the third-party service provider of the Third-Party Account. By granting us access to any Third-Party Accounts, you understand that (1) we may access, make available, and store (if applicable) any content that you have provided to and stored in your Third-Party Account (the “Social Network Content”) so that it is available on and through the Site via your account, including without limitation any friend lists and (2) we may submit to and receive from your Third-Party Account additional information to the extent you are notified when you link your account with the Third-Party Account. Depending on the Third-Party Accounts you choose and subject to the privacy settings that you have set in such Third-Party Accounts, personally identifiable information that you post to your Third- Party Accounts may be available on and through your account on the Site. Please note that if a Third-Party Account or associated service becomes unavailable or our access to such Third Party Account is terminated by the third-party service provider, then Social Network Content may no longer be available on and through the Site. You will have the ability to disable the connection between your account on the Site and your Third-Party Accounts at any time. PLEASE NOTE THAT YOUR RELATIONSHIP WITH THE THIRD-PARTY SERVICE PROVIDERS ASSOCIATED WITH YOUR THIRD-PARTY ACCOUNTS IS GOVERNED SOLELY BY YOUR AGREEMENT(S) WITH SUCH THIRD-PARTY SERVICE PROVIDERS. We make no effort to review any Social Network Content for any purpose, including but not limited to, for accuracy, legality, or non-infringement, and we are not responsible for any Social Network Content. You acknowledge and agree that we may access your email address book associated with a Third-Party Account and your contacts list stored on your mobile device or tablet computer solely for purposes of identifying and informing you of those contacts who have also registered to use the Site. You can deactivate the connection between the Site and your Third-Party Account by contacting us using the contact information below or through your account settings (if applicable). We will attempt to delete any information stored on our servers that was obtained through such Third-Party Account, except the username and profile picture that become associated with your account.
9. SUBMISSIONS
You acknowledge and agree that any questions, comments, suggestions, ideas, feedback, or other information regarding the Site ("Submissions") provided by you to us are non-confidential and shall become our sole property. We shall own exclusive rights, including all intellectual property rights, and shall be entitled to the unrestricted use and dissemination of these Submissions for any lawful purpose, commercial or otherwise, without acknowledgment or compensation to you. You hereby waive all moral rights to any such Submissions, and you hereby warrant that any such Submissions are original with you or that you have the right to submit such Submissions. You agree there shall be no recourse against us for any alleged or actual infringement or misappropriation of any proprietary right in your Submissions.
10. SITE MANAGEMENT
We reserve the right, but not the obligation, to: (1) monitor the Site for violations of these Terms of Use; (2) take appropriate legal action against anyone who, in our sole discretion, violates the law or these Terms of Use, including without limitation, reporting such user to law enforcement authorities; (3) in our sole discretion and without limitation, refuse, restrict access to, limit the availability of, or disable (to the extent technologically feasible) any of your Contributions or any portion thereof; (4) in our sole discretion and without limitation, notice, or liability, to remove from the Site or otherwise disable all files and content that are excessive in size or are in any way burdensome to our systems; and (5) otherwise manage the Site in a manner designed to protect our rights and property and to facilitate the proper functioning of the Site.
11. COPYRIGHT INFRINGEMENTS
We respect the intellectual property rights of others. If you believe that any material
available on or through the Site infringes upon any copyright you own or control, please immediately notify us using the contact information provided below (a
“Notification”). A copy of your Notification will be sent to the person who posted or stored the material addressed in the Notification. Please be advised that pursuant to applicable law you may be held liable for damages if you make material misrepresentations in a Notification. Thus, if you are not sure that material located on or linked to by the Site infringes your copyright, you should consider first contacting an attorney.
12. TERM AND TERMINATION
These Terms of Use shall remain in full force and effect while you use the Site. WITHOUT LIMITING ANY OTHER PROVISION OF THESE TERMS OF USE, WE RESERVE THE RIGHT TO, IN OUR SOLE DISCRETION AND WITHOUT NOTICE OR LIABILITY, DENY ACCESS TO AND USE OF THE SITE (INCLUDING BLOCKING CERTAIN IP ADDRESSES), TO ANY PERSON FOR ANY REASON OR FOR NO REASON, INCLUDING WITHOUT LIMITATION FOR BREACH OF ANY REPRESENTATION, WARRANTY, OR COVENANT CONTAINED IN THESE TERMS OF USE OR OF ANY APPLICABLE LAW OR REGULATION. WE MAY TERMINATE YOUR USE OR PARTICIPATION IN THE SITE OR DELETE YOUR ACCOUNT
AND ANY CONTENT OR INFORMATION THAT YOU POSTED AT ANY TIME, WITHOUT WARNING, IN OUR SOLE DISCRETION.
If we terminate or suspend your account for any reason, you are prohibited from registering and creating a new account under your name, a fake or borrowed name, or the name of any third party, even if you may be acting on behalf of the third party. In addition to terminating or suspending your account, we reserve the right to take appropriate legal action, including without limitation pursuing civil, criminal, and injunctive redress.
13. MODIFICATIONS AND INTERRUPTIONS
We reserve the right to change, modify, or remove the contents of the Site at any time or for any reason at our sole discretion without notice. However, we have no obligation to update any information on our Site. We also reserve the right to modify or discontinue all or part of the Site without notice at any time. We will not be liable to you or any third party for any modification, price change, suspension, or discontinuance of the Site.
We cannot guarantee the Site will be available at all times. We may experience hardware, software, or other problems or need to perform maintenance related to the Site, resulting in interruptions, delays, or errors. We reserve the right to change, revise, update, suspend, discontinue, or otherwise modify the Site at any time or for any reason without notice to you. You agree that we have no liability whatsoever for any loss, damage, or inconvenience caused by your inability to access or use the Site during any downtime or discontinuance of the Site. Nothing in these Terms of Use will be construed to obligate us to maintain and support the Site or to supply any corrections, updates, or releases in connection therewith.
14. GOVERNING LAW
These Terms shall be governed by and defined following the laws of Canada. RegistrationMagic and yourself irrevocably consent that the courts of Canada shall have exclusive jurisdiction to resolve any dispute which may arise in connection with these terms.
15. DISPUTE RESOLUTION
Informal Negotiations
To expedite resolution and control the cost of any dispute, controversy, or claim related to these Terms of Use (each "Dispute" and collectively, the “Disputes”) brought by either you or us (individually, a “Party” and collectively, the “Parties”), the Parties agree to first attempt to negotiate any Dispute (except those Disputes expressly provided below) informally for at least sixty (60) days before initiating arbitration. Such informal negotiations commence upon written notice from one Party to the other Party.
Binding Arbitration
Any dispute arising out of or in connection with this contract, including any question regarding its existence, validity, or termination, shall be referred to and finally resolved by the International Commercial Arbitration Court under the European Arbitration Chamber (Belgium, Brussels, Avenue Louise, 146) according to the Rules of this ICAC, which, as a result of referring to it, is considered as the part of this clause. The number of arbitrators shall be three (3). The seat, or legal place, of arbitration shall be Toronto, Canada. The language of the proceedings shall be English. The governing law of the contract shall be the substantive law of Canada.
Restrictions
The Parties agree that any arbitration shall be limited to the Dispute between the Parties individually. To the full extent permitted by law, (a) no arbitration shall be joined with any other proceeding; (b) there is no right or authority for any Dispute to be arbitrated on a class-action basis or to utilize class action procedures; and (c) there is no right or authority for any Dispute to be brought in a purported representative capacity on behalf of the general public or any other persons.
Exceptions to Informal Negotiations and Arbitration
The Parties agree that the following Disputes are not subject to the above provisions concerning informal negotiations and binding arbitration: (a) any Disputes seeking to enforce or protect, or concerning the validity of, any of the intellectual property rights of a Party; (b) any Dispute related to, or arising from, allegations of theft, piracy, invasion of privacy, or unauthorized use; and (c) any claim for injunctive relief. If this provision is found to be illegal or unenforceable, then neither Party will elect to arbitrate any Dispute falling within that portion of this provision found to be illegal or unenforceable and such Dispute shall be decided by a court of competent jurisdiction within the courts listed for jurisdiction above, and the Parties agree to submit to the personal jurisdiction of that court.
16. CORRECTIONS
There may be information on the Site that contains typographical errors, inaccuracies, or omissions, including descriptions, pricing, availability, and various other information. We reserve the right to correct any errors, inaccuracies, or omissions and to change or update the information on the Site at any time, without prior notice.

18. LIMITATIONS OF LIABILITY
IN NO EVENT WILL WE OR OUR DIRECTORS, EMPLOYEES, OR AGENTS BE LIABLE TO YOU OR ANY THIRD PARTY FOR ANY DIRECT, INDIRECT, CONSEQUENTIAL, EXEMPLARY, INCIDENTAL, SPECIAL, OR PUNITIVE DAMAGES, INCLUDING LOST PROFIT, LOST REVENUE, LOSS OF DATA, OR OTHER DAMAGES ARISING FROM YOUR USE OF THE SITE, EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.
19. INDEMNIFICATION
You agree to defend, indemnify, and hold us harmless, including our subsidiaries, affiliates, and all of our respective officers, agents, partners, and employees, from and against any loss, damage, liability, claim, or demand, including reasonable attorneys’ fees and expenses, made by any third party due to or arising out of: (1) your Contributions; (2) use of the Site; (3) breach of these Terms of Use; (4) any breach of your representations and warranties set forth in these Terms of Use; (5) your violation of the rights of a third party, including but not limited to intellectual property rights; or (6) any overt harmful act toward any other user of the Site with whom you connected via the Site. Notwithstanding the foregoing, we reserve the right, at your expense, to assume the exclusive defense and control of any matter for which you are required to indemnify us, and you agree to cooperate, at your expense, with our defense of such claims. We will use reasonable efforts to notify you of any such claim, action, or proceeding which is subject to this indemnification upon becoming aware of it.
20. USER DATA
We will maintain certain data that you transmit to the Site for the purpose of managing the performance of the Site, as well as data relating to your use of the Site. Although we perform regular routine backups of data, you are solely responsible for all data that you transmit or that relates to any activity you have undertaken using the Site. You agree that we shall have no liability to you for any loss or corruption of any such data, and you hereby waive any right of action against us arising from any such loss or corruption of such data.
21. ELECTRONIC COMMUNICATIONS, TRANSACTIONS, AND SIGNATURES
Visiting the Site, sending us emails, and completing online forms constitute electronic communications. You consent to receive electronic communications, and you agree that all agreements, notices, disclosures, and other communications we provide to you electronically, via email and on the Site, satisfy any legal requirement that such communication be in writing. YOU HEREBY AGREE TO THE USE OF ELECTRONIC SIGNATURES, CONTRACTS, ORDERS, AND OTHER RECORDS, AND TO ELECTRONIC DELIVERY OF NOTICES, POLICIES, AND RECORDS OF TRANSACTIONS INITIATED OR COMPLETED BY US OR VIA THE SITE. You hereby waive any rights or requirements under any statutes, regulations, rules, ordinances, or other laws in any jurisdiction which require an original signature or delivery or retention of non-electronic records, or to payments or the granting of credits by any means other than electronic means.
22. CALIFORNIA USERS AND RESIDENTS
If any complaint with us is not satisfactorily resolved, you can contact the Complaint Assistance Unit of the Division of Consumer Services of the California Department of Consumer Affairs in writing at 1625 North Market Blvd., Suite N 112, Sacramento, California 95834 or by telephone at (800) 952-5210 or (916) 445-1254.
23. MISCELLANEOUS
These Terms of Use and any policies or operating rules posted by us on the Site or in respect to the Site constitute the entire agreement and understanding between you and us. Our failure to exercise or enforce any right or provision of these Terms of Use shall not operate as a waiver of such right or provision. These Terms of Use operate to the fullest extent permissible by law. We may assign any or all of our rights and obligations to others at any time. We shall not be responsible or liable for any loss, damage, delay, or failure to act caused by any cause beyond our reasonable control. If any provision or part of a provision of these Terms of Use is determined to be unlawful, void, or unenforceable, that provision or part of the provision is deemed severable from these Terms of Use and does not affect the validity and enforceability of any remaining provisions. There is no joint venture, partnership, employment or agency relationship created between you and us as a result of these Terms of Use or use of the Site. You agree that these Terms of Use will not be construed against us by virtue of having drafted them. You hereby waive any and all defenses you may have based on the electronic form of these Terms of Use and the lack of signing by the parties hereto to execute these Terms of Use.
24. CONTACT US
In order to resolve a complaint regarding the Site or to receive further information regarding use of the Site, please contact us at:
RegistrationMagic Toronto
Toronto, Ontario ZX4 WE2 Canada
Phone: 900000000 contact@registrationmagic.com', 'I accept the terms of service.');
        $this->create_template_row_structure($form_id, array($field_terms_id), 1, '1');
        
        //What is your emotional state?
        $field_emotional_id = $this->create_template_select_field($form_id, 1, 'What is your emotional state?', '', array("Happy", "Excited", "Confused", "Sad", "Anxious", "Upset"), 10);
        $this->create_template_row_structure($form_id, array($field_emotional_id), 1, '1');
    }
    
    public function create_contact_template_c3($form_id){
        //description
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "rich text", "<p>Your suggestions mean everything to us. Please fill out the small form below to help us make things better for you and the others.</p>","",1);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        //divider
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",2);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        // Name
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', ' ', '', 1, 3);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', ' ', '', 1, 4);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');

        //Email Contact
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', ' ', 5);
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Contact Number', '', 6);
        $this->create_template_row_structure($form_id, array($field_email_id,$field_mobile_id), 1, '1:1');
        
        //I have a suggestion for
        $field_sugg_id = $this->create_template_select_field($form_id, 1, 'I have a suggestion for', '', array("Sales", "Marketing", "Operations", "Products", "Staff", "Team", "HR", "Finance", "Billings", "Other"), 7);
        $this->create_template_row_structure($form_id, array($field_sugg_id), 1, '1');
        
        //Suggestions regarding
        $field_reg_id = $this->create_template_select_field($form_id, 1, 'Suggestions regarding', '', array("Process", "Improvements", "Features", "New Idea", "Product", "Other"), 8);
        $this->create_template_row_structure($form_id, array($field_reg_id), 1, '1');
        //Subject
        $field_subject_id = $this->create_template_text_field($form_id, 1, 'Subject', ' ', '',1, 9);
        $this->create_template_row_structure($form_id, array($field_subject_id), 1, '1');
        
        // Describe your suggestions
        $field_msg_id = $this->create_template_textarea_field($form_id, 1, 'Describe your suggestions', ' ', '', 1, 4, 4, 10);
        $this->create_template_row_structure($form_id, array($field_msg_id), 1, '1');
        //Suggestion For
        $field_radio_id = $this->create_template_radio_field($form_id, 1, 'Want to receive updates regarding your suggestions progress?', '', 11, array('Yes', 'No', 'Not Sure'));
        $this->create_template_row_structure($form_id, array($field_radio_id), 1, '1');

    }

    public function create_contact_template_c4($form_id){
        // Name
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', ' ', '',1, 1);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', ' ', '', 1, 2);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');
        
        //Email & Contact
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', '', 3);
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Contact Number', '', 4);
        $this->create_template_row_structure($form_id, array($field_email_id,$field_mobile_id), 1, '1:1');
        
        //Address
        $field_address_id = $this->create_template_address_field($form_id, 1, "Address", "", 5);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');
        
        //Requesting quote for
        $field_quote_id = $this->create_template_radio_field($form_id, 1, 'Requesting quote for', '', 6, array('Individual','Business','Other'));
        $this->create_template_row_structure($form_id, array($field_quote_id), 1, '1');
        
        //Business / Organization name
        $conditions = array('rules'=>array(
                                    'c_'.$field_quote_id.'_0'=>array('controlling_field'=>$field_quote_id,'op'=>'==','values'=>array('Business'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_business_id = $this->create_template_text_field($form_id, 1, 'Business / Organization name', ' ', '',1, 7, $conditions);
        $this->create_template_row_structure($form_id, array($field_business_id), 1, '1');
        
        //Business / Organization name
        $conditions = array('rules'=>array(
                                    'c_'.$field_quote_id.'_0'=>array('controlling_field'=>$field_quote_id,'op'=>'==','values'=>array('Other'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_other_id = $this->create_template_text_field($form_id, 1, 'Please specify', ' ', '',1, 8, $conditions);
        $this->create_template_row_structure($form_id, array($field_other_id), 1, '1');
        
        //Urgency of your request
        $field_urgency_id = $this->create_template_radio_field($form_id, 1, 'Urgency of your request', '', 9, array('Normal','Low','High'));
        $this->create_template_row_structure($form_id, array($field_urgency_id), 1, '1');
        
        //Need quote for
        $field_need_id = $this->create_template_select_field($form_id, 1, 'Need quote for', '', array('Marketing', 'Services', 'Products','Other'), 10);
        $this->create_template_row_structure($form_id, array($field_need_id), 1, '1');
        
        //Marketing Services
        $conditions = array('rules'=>array(
                                    'c_'.$field_need_id.'_0'=>array('controlling_field'=>$field_need_id,'op'=>'==','values'=>array('Marketing'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_market_id = $this->create_template_select_field($form_id, 1, 'Marketing Services', '', array('Content writing', 'Graphics design', 'Email marketing','PPC','Other'), 11, $conditions);
        $this->create_template_row_structure($form_id, array($field_market_id), 1, '1');
        
        //Services
        $conditions = array('rules'=>array(
                                    'c_'.$field_need_id.'_0'=>array('controlling_field'=>$field_need_id,'op'=>'==','values'=>array('Services'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_service_id = $this->create_template_select_field($form_id, 1, 'Services', '', array('Website Development', 'Website Management', 'Service Management', 'Finance management',' Accounts Management','HR Management','Other'), 12, $conditions);
        $this->create_template_row_structure($form_id, array($field_service_id), 1, '1');
        
        //Products
        $conditions = array('rules'=>array(
                                    'c_'.$field_need_id.'_0'=>array('controlling_field'=>$field_need_id,'op'=>'==','values'=>array('Products'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_product_id = $this->create_template_select_field($form_id, 1, 'Products', '', array('New product development', ' Existing product maintenance', 'Other'), 13, $conditions);
        $this->create_template_row_structure($form_id, array($field_product_id), 1, '1');
        
        // What do you need?
        $conditions = array('rules'=>array(
                                    'c_'.$field_market_id.'_0'=>array('controlling_field'=>$field_market_id,'op'=>'==','values'=>array('Content writing')),
                                    'c_'.$field_market_id.'_1'=>array('controlling_field'=>$field_market_id,'op'=>'==','values'=>array('Graphics design')),
                                    'c_'.$field_market_id.'_2'=>array('controlling_field'=>$field_market_id,'op'=>'==','values'=>array('Email marketing')),
                                    'c_'.$field_market_id.'_3'=>array('controlling_field'=>$field_market_id,'op'=>'==','values'=>array('PPC')),
                                    'c_'.$field_service_id.'_0'=>array('controlling_field'=>$field_service_id,'op'=>'==','values'=>array('Website Development')),
                                    'c_'.$field_service_id.'_1'=>array('controlling_field'=>$field_service_id,'op'=>'==','values'=>array('Website Management')),
                                    'c_'.$field_service_id.'_2'=>array('controlling_field'=>$field_service_id,'op'=>'==','values'=>array('Service Management')),
                                    'c_'.$field_service_id.'_3'=>array('controlling_field'=>$field_service_id,'op'=>'==','values'=>array('Finance Management')),
                                    'c_'.$field_service_id.'_4'=>array('controlling_field'=>$field_service_id,'op'=>'==','values'=>array('Accounts Management')),
                                    'c_'.$field_service_id.'_5'=>array('controlling_field'=>$field_service_id,'op'=>'==','values'=>array('HR Management')),
                                    'c_'.$field_product_id.'_0'=>array('controlling_field'=>$field_product_id,'op'=>'==','values'=>array('New product development')),
                                    'c_'.$field_product_id.'_1'=>array('controlling_field'=>$field_product_id,'op'=>'==','values'=>array('Existing product maintenance'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_what_need_id = $this->create_template_text_field($form_id, 1, 'What do you need?', ' ', '',1, 14, $conditions);
        $this->create_template_row_structure($form_id, array($field_what_need_id), 1, '1');
        
        //Please Specify
        $conditions = array('rules'=>array(
                                    'c_'.$field_need_id.'_0'=>array('controlling_field'=>$field_need_id,'op'=>'==','values'=>array('Other')),
                                    'c_'.$field_service_id.'_1'=>array('controlling_field'=>$field_service_id,'op'=>'==','values'=>array('Other')),
                                    'c_'.$field_product_id.'_2'=>array('controlling_field'=>$field_product_id,'op'=>'==','values'=>array('Other')),
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_specify_id = $this->create_template_text_field($form_id, 1, 'Please Specify', ' ', '',1, 15, $conditions);
        $this->create_template_row_structure($form_id, array($field_specify_id), 1, '1');
        
        //Want to share anything with us? Upload here
        $field_file_id = $this->create_template_file_upload_field($form_id, 1, "Want to share anything with us? Upload here", "", 16, "PDF|DOCX|PNG|JPG|JPEG|CSV");
        $this->create_template_row_structure($form_id, array($field_file_id), 1, '1');
        
        //Any more details you'd like us to know?
        $field_req_id = $this->create_template_textarea_field($form_id, 1, "Any more details you'd like us to know?", ' ', '',1, 17, 4, 12);
        $this->create_template_row_structure($form_id, array($field_req_id), 1, '1');
        
        $field_terms_id = $this->create_template_termscondition_field($form_id, 1, $label='Terms and Conditions', '', 18, 'We will use your provided information to the best of our knowledge and provide you with the quotes accordingly.','I agree');
        $this->create_template_row_structure($form_id, array($field_terms_id), 1, '1');
        
    }

    public function create_contact_template_c5($form_id){
        // Name
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', ' ', '',1, 1);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', ' ', '',1, 2);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');
        //Phone No.
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Phone Number', '',1, 3);
        $this->create_template_row_structure($form_id, array($field_mobile_id), 1, '1');

        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Work Email', ' ', 4);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');

        //Business Type 
        $field_business_id = $this->create_template_select_field($form_id, 1, 'Business Type', '', array('First Choice', 'Second Choice', 'Third Choice'), 5);
        $this->create_template_row_structure($form_id, array($field_business_id), 1, '1');
        // Company Name
        $field_company_id = $this->create_template_text_field($form_id, 1, 'Company Name', ' ', '',0, 6);
        $this->create_template_row_structure($form_id, array($field_company_id), 1, '1');

        //Inquiry Details
        $field_req_id = $this->create_template_textarea_field($form_id, 1, 'Inquiry Details', ' ', '',1, 7, 4, 12);
        $this->create_template_row_structure($form_id, array($field_req_id), 1, '1');

    }

    public function create_contact_template_c6($form_id){
        //Name
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', 'Enter First Name', '',1, 1);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', 'Enter Last Name', '',1, 2);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1',null,"Attendee's details");
        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 3);
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Phone Number', '',1, 4);
        $this->create_template_row_structure($form_id, array($field_email_id,$field_mobile_id), 1, '1:1');
        
        $field_address_id = $this->create_template_address_field($form_id, 1, "Address", "", 5);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');
        
        $field_company_id = $this->create_template_text_field($form_id, 1, "Company Name", '', '',0, 6);
        $this->create_template_row_structure($form_id, array($field_company_id), 1, '1');
        
        $field_conference_id = $this->create_template_select_field($form_id, 1, 'Which conference are you interested in?', '', array('Marketing', 'Business Development', 'Human Resource', 'Other'), 7);
        $this->create_template_row_structure($form_id, array($field_conference_id), 1, '1');
        
        
        $field_session_id = $this->create_template_checkbox_field($form_id, 1, 'Which sessions do you plan on attending?', '', 8, array('Morning session','Afternoon session','Night session', 'Banquets', 'Workshops'));
        $this->create_template_row_structure($form_id, array($field_session_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_session_id.'_0'=>array('controlling_field'=>$field_session_id,'op'=>'==','values'=>array('Morning session')),
                                    'c_'.$field_session_id.'_1'=>array('controlling_field'=>$field_session_id,'op'=>'==','values'=>array('Afternoon session')),
                                    'c_'.$field_session_id.'_2'=>array('controlling_field'=>$field_session_id,'op'=>'==','values'=>array('Workshops')),
                                    'c_'.$field_session_id.'_3'=>array('controlling_field'=>$field_session_id,'op'=>'==','values'=>array('Banquets')),
                                    'c_'.$field_session_id.'_4'=>array('controlling_field'=>$field_session_id,'op'=>'==','values'=>array('Night session'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_session_1_id = $this->create_template_text_field($form_id, 1, 'In a couple of words please explain why you want to attain this session?', '', '',1, 9, $conditions);
        $this->create_template_row_structure($form_id, array($field_session_1_id), 1, '1',null);
        
        $field_member_id = $this->create_template_number_field($form_id, 1, 'How many members are attending the conference?', 'Enter the totals numbers of members', '',0, 10);
        $this->create_template_row_structure($form_id, array($field_member_id), 1, '1');

        $field_stay_id = $this->create_template_radio_field($form_id, 1, 'Will you be staying overnight?', '', 11, array('Yes',"No"));
        $this->create_template_row_structure($form_id, array($field_stay_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_stay_id.'_0'=>array('controlling_field'=>$field_stay_id,'op'=>'==','values'=>array('Yes'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_stay_1_id = $this->create_template_number_field($form_id, 1, 'If yes, how many members?', '', '',1, 12, $conditions);
        $this->create_template_row_structure($form_id, array($field_stay_1_id), 1, '1',null);
        
        $field_product_1_id = $this->create_template_product_field($form_id, 1, "Tier 1", "Bronze - (Snacks and Breakfast/ Lunch)", $product_type="fixed", $product_value='199.95',$option_label=array(), $option_value=array(), "",13);
        $this->create_template_row_structure($form_id, array($field_product_1_id), 1, '1', null, 'Please select your preferred package'); 
        
        $field_product_2_id = $this->create_template_product_field($form_id, 1, "Tier 2", "Silver - (Snacks and Breakfast/ Lunch/ Dinner)", $product_type="fixed", $product_value='299.95',$option_label=array(), $option_value=array(), "",14);
        $this->create_template_row_structure($form_id, array($field_product_2_id), 1, '1'); 
        
        $field_product_3_id = $this->create_template_product_field($form_id, 1, "Tier 3", "Gold - (Snacks, Drinks, Breakfast/ Lunch/ Dinner)", $product_type="fixed", $product_value='399.95',$option_label=array(), $option_value=array(), "",15);
        $this->create_template_row_structure($form_id, array($field_product_3_id), 1, '1'); 
        
        $field_msg_id = $this->create_template_textarea_field($form_id, 1, 'Comments or Questions', '', '', 1, 16, 4, 12);
        $this->create_template_row_structure($form_id, array($field_msg_id), 1, '1');
    }

    public function create_contact_template_c7($form_id){
        //Name
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 5);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 6);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');
        //Email 
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', '', 3);
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Contact Number', '',1, 4);
        $this->create_template_row_structure($form_id, array($field_email_id,$field_mobile_id), 1, '1:1');
        
        $field_address_id = $this->create_template_address_field($form_id, 1, "Address", "", 5);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');
        
        $field_year_id = $this->create_template_calender_field($form_id,1,"Year of Registration", "",1, 6);
        $this->create_template_row_structure($form_id, array($field_year_id), 1, '1');
        
        $field_manufacturer_id = $this->create_template_select_field($form_id, 1, 'Manufacturer', '', array('Honda', 'Ford', 'Volkswagen','Other'), 7);
        $this->create_template_row_structure($form_id, array($field_manufacturer_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_manufacturer_id.'_0'=>array('controlling_field'=>$field_manufacturer_id,'op'=>'==','values'=>array('Other'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_manufacturer_1_id = $this->create_template_text_field($form_id, 1, 'Please specify', '', '',1, 8, $conditions);
        $this->create_template_row_structure($form_id, array($field_manufacturer_1_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_manufacturer_id.'_0'=>array('controlling_field'=>$field_manufacturer_id,'op'=>'==','values'=>array('Honda'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_manufacturer_2_id = $this->create_template_select_field($form_id, 1, 'Model Name (Honda)', '', array('HR-V (Petrol)', 'Civic (Diesel)', 'HR-V (Diesel)','Accord','Passport','Odyssey','CR-V (Electric)','Other'), 9, $conditions);
        $this->create_template_row_structure($form_id, array($field_manufacturer_2_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_manufacturer_2_id.'_0'=>array('controlling_field'=>$field_manufacturer_2_id,'op'=>'==','values'=>array('Other'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_manufacturer_honda_1_id = $this->create_template_text_field($form_id, 1, 'Please specify', '', '',1, 10, $conditions);
        $this->create_template_row_structure($form_id, array($field_manufacturer_honda_1_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_manufacturer_id.'_0'=>array('controlling_field'=>$field_manufacturer_id,'op'=>'==','values'=>array('Ford'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_manufacturer_ford_id = $this->create_template_select_field($form_id, 1, 'Model Name (Ford)', '', array('Ecosport', 'Escape', 'Bronco','Explorer','Edge','Mustang','Expedition','Other'), 11, $conditions);
        $this->create_template_row_structure($form_id, array($field_manufacturer_ford_id), 1, '1');
        $conditions = array('rules'=>array(
                                    'c_'.$field_manufacturer_ford_id.'_0'=>array('controlling_field'=>$field_manufacturer_ford_id,'op'=>'==','values'=>array('Other'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_manufacturer_ford_1_id = $this->create_template_text_field($form_id, 1, 'Please specify', '', '',1, 12, $conditions);
        $this->create_template_row_structure($form_id, array($field_manufacturer_ford_1_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_manufacturer_id.'_0'=>array('controlling_field'=>$field_manufacturer_id,'op'=>'==','values'=>array('Volkswagen'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_manufacturer_volkswagen_id = $this->create_template_select_field($form_id, 1, 'Model Name (Volkswagen)', '', array('Ecosport', 'Escape', 'Bronco','Explorer','Edge','Mustang','Expedition','Other'), 13, $conditions);
        $this->create_template_row_structure($form_id, array($field_manufacturer_volkswagen_id), 1, '1');
        $conditions = array('rules'=>array(
                                    'c_'.$field_manufacturer_volkswagen_id.'_0'=>array('controlling_field'=>$field_manufacturer_volkswagen_id,'op'=>'==','values'=>array('Other'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_manufacturer_volkswagen_1_id = $this->create_template_text_field($form_id, 1, 'Please specify', '', '',1, 14, $conditions);
        $this->create_template_row_structure($form_id, array($field_manufacturer_volkswagen_1_id), 1, '1');
        
        $field_vin_id = $this->create_template_text_field($form_id, 1, 'Vehicle identification number (VIN)', ' ', '',1, 15);
        $this->create_template_row_structure($form_id, array($field_vin_id), 1, '1');
        
        $field_insurance_id = $this->create_template_radio_field($form_id, 1, 'Is your car insured?', '', 16, array('Yes','No','Not Sure'));
        $conditions = array('rules'=>array(
                                    'c_'.$field_insurance_id.'_0'=>array('controlling_field'=>$field_insurance_id,'op'=>'==','values'=>array('Yes'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_insc_provider_id = $this->create_template_text_field($form_id, 1, 'Please enter your insurance provider', ' ', '',1, 17,$conditions);
        $this->create_template_row_structure($form_id, array($field_insurance_id,$field_insc_provider_id), 1, '1:1');
        
        $field_insurance_used_id = $this->create_template_radio_field($form_id, 1, 'Have you ever used your insurance?', '', 18, array('Yes','No'),$conditions);
        $this->create_template_row_structure($form_id, array($field_insurance_used_id), 1, '1');
        
        $field_km_id = $this->create_template_number_field($form_id, 1, 'Kilometres driven', '', '',0, 19);
        $this->create_template_row_structure($form_id, array($field_km_id), 1, '1');
        
        
        $field_service_id = $this->create_template_radio_field($form_id, 1, 'Do you want to', '', 20, array('Get insurance quote','Book a service','Book a repair','Book an inspection','Other'));
        $this->create_template_row_structure($form_id, array($field_service_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_service_id.'_0'=>array('controlling_field'=>$field_service_id,'op'=>'==','values'=>array('Get insurance quote'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        
        $field_service_1_id = $this->create_template_checkbox_field($form_id, 1, 'Do you or your companions have any hereditary conditions/diseases?', '', 21, array('Comprehensive car insurance','Personal accident cover','Third-Party liability only cover','Not sure'),$conditions);
        $this->create_template_row_structure($form_id, array($field_service_1_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_service_1_id.'_0'=>array('controlling_field'=>$field_service_1_id,'op'=>'==','values'=>array('Comprehensive car insurance')),
                                    'c_'.$field_service_1_id.'_1'=>array('controlling_field'=>$field_service_1_id,'op'=>'==','values'=>array('Personal accident cover')),
                                    'c_'.$field_service_1_id.'_2'=>array('controlling_field'=>$field_service_1_id,'op'=>'==','values'=>array('Third-Party liability only cover'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        
        $field_service_1_1_id = $this->create_template_checkbox_field($form_id, 1, 'Do you or your companions have any hereditary conditions/diseases?', '', 22, array('ABC','XYZ','TUR','All available'),$conditions);
        $this->create_template_row_structure($form_id, array($field_service_1_1_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_service_1_1_id.'_0'=>array('controlling_field'=>$field_service_1_1_id,'op'=>'==','values'=>array('Not Sure'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_service_1_0_id = $this->create_template_text_field($form_id, 1, 'Please give us more details to help you serve better', ' ', '',1, 23,$conditions);
        $this->create_template_row_structure($form_id, array($field_service_1_0_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_service_1_1_id.'_0'=>array('controlling_field'=>$field_service_1_1_id,'op'=>'==','values'=>array('ABC')),
                                    'c_'.$field_service_1_1_id.'_1'=>array('controlling_field'=>$field_service_1_1_id,'op'=>'==','values'=>array('TUR')),
                                    'c_'.$field_service_1_1_id.'_2'=>array('controlling_field'=>$field_service_1_1_id,'op'=>'==','values'=>array('XYZ')),
                                    'c_'.$field_service_1_1_id.'_3'=>array('controlling_field'=>$field_service_1_1_id,'op'=>'==','values'=>array('All available'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_service_1_1_1_id = $this->create_template_select_field($form_id, 1, 'Want to get the quotes through', '', array('Email', 'Phone', 'Mail', 'All'), 24, $conditions);
        $this->create_template_row_structure($form_id, array($field_service_1_1_1_id), 1, '1');
        
        
        
        
        
        // Service 2
        $conditions = array('rules'=>array(
                                    'c_'.$field_service_id.'_0'=>array('controlling_field'=>$field_service_id,'op'=>'==','values'=>array('Book a service'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        
        $field_service_2_id = $this->create_template_checkbox_field($form_id, 1, 'Select service type', '', 25, array('Full service','Cleaning','General service','Specific service','Warrant of fitness'),$conditions);
        $this->create_template_row_structure($form_id, array($field_service_2_id), 1, '1');
        
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_service_2_id.'_0'=>array('controlling_field'=>$field_service_2_id,'op'=>'==','values'=>array('Specific service'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_service_2_1_id = $this->create_template_text_field($form_id, 1, 'Please specify', '', '',1, 26, $conditions);
        $this->create_template_row_structure($form_id, array($field_service_2_1_id), 1, '1');
        
        
        // Service 3
        $conditions = array('rules'=>array(
                                    'c_'.$field_service_id.'_0'=>array('controlling_field'=>$field_service_id,'op'=>'==','values'=>array('Book a repair'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        
        $field_service_3_id = $this->create_template_checkbox_field($form_id, 1, 'What needs to be repaired?', '', 27, array('Glass (Windshield)','Glass (Windows)','Security system','Engine','Tyres','Any other'),$conditions);
        $this->create_template_row_structure($form_id, array($field_service_3_id), 1, '1');
        
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_service_3_id.'_0'=>array('controlling_field'=>$field_service_3_id,'op'=>'==','values'=>array('Any other'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_service_3_1_id = $this->create_template_text_field($form_id, 1, 'Please specify', '', '',1, 28, $conditions);
        $this->create_template_row_structure($form_id, array($field_service_3_1_id), 1, '1');
        
        // Service 4
        $conditions = array('rules'=>array(
                                    'c_'.$field_service_id.'_0'=>array('controlling_field'=>$field_service_id,'op'=>'==','values'=>array('Book an inspection'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        
        $field_service_4_id = $this->create_template_radio_field($form_id, 1, 'Inspection type available', '', 29, array('General - $89','Intense - $129','Overall - $149 (Inspect every part of the car)'),$conditions);
        $this->create_template_row_structure($form_id, array($field_service_4_id), 1, '1');
        
        //Servie 5 Other
        $conditions = array('rules'=>array(
                                    'c_'.$field_service_id.'_0'=>array('controlling_field'=>$field_service_id,'op'=>'==','values'=>array('Other'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_service_5_id = $this->create_template_text_field($form_id, 1, 'Please specify', '', '',1, 30, $conditions);
        $this->create_template_row_structure($form_id, array($field_service_5_id), 1, '1');
       
    }

    public function create_contact_template_c8($form_id){
        //Name
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 1);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 2);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');
        //Email 
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', '', 3);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
        //Product
        $field_product_id = $this->create_template_select_field($form_id, 1, 'Please select our product?', '', array('Product A', 'Product B', 'Product C'), 4);
        $this->create_template_row_structure($form_id, array($field_product_id), 1, '1');

        //req
        $field_request_id = $this->create_template_text_field($form_id, 1, 'What would you like us to add?', '', '',1, 5);
        $this->create_template_row_structure($form_id, array($field_request_id), 1, '1:1');

        // Message
        $field_msg_id = $this->create_template_textarea_field($form_id, 1, "Please explain how you'd like this feature to work", '', '', 1, 6, 4, 12);
        $this->create_template_row_structure($form_id, array($field_msg_id), 1, '1');
        
        $field_update_id = $this->create_template_radio_field($form_id, 1, 'Would you like to receive updates about your requested features?', '', 7, array('Yes','No','Not Sure'));
        $this->create_template_row_structure($form_id, array($field_update_id), 1, '1');

    }

    public function create_contact_template_c9($form_id){
        //Name
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 1);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 2);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1',null,'Student Details');
        //Email 
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', '', 3);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
        //Student ID
        $field_student_id = $this->create_template_text_field($form_id, 1, 'Student ID', '', '',1, 4);
        $this->create_template_row_structure($form_id, array($field_student_id), 1, '1');

        //Assignment Name
        $field_assignment_id = $this->create_template_text_field($form_id, 1, 'Assignment Name', '', '',1, 5);
        $this->create_template_row_structure($form_id, array($field_assignment_id), 1, '1');

        //Status
        $field_status_id = $this->create_template_select_field($form_id, 1, 'Status', '', array('Complete', 'Pending', 'Unknown'), 6);
        $this->create_template_row_structure($form_id, array($field_status_id), 1, '1');

        //Grade
        $field_grade_id = $this->create_template_select_field($form_id, 1, 'Grade', '', array('A', 'B', 'C', 'D', 'E', 'F'), 7);
        $this->create_template_row_structure($form_id, array($field_grade_id), 1, '1');

        // Comment
        $field_msg_id = $this->create_template_textarea_field($form_id, 1, "Comment", '', '', 1, 4, 4, 12);
        $this->create_template_row_structure($form_id, array($field_msg_id), 1, '1');

        //Teacher Details
        $field_tfirst_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 1);
        $field_tlast_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 2);
        $this->create_template_row_structure($form_id, array($field_tfirst_id, $field_tlast_id), 1, '1:1',null,'Teachers Details');
    }
    public function create_contact_template_c10($form_id){
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 1);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 2);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');

        //Email 
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', '', 3);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
        //Product
        //$field_product_id = $this->create_template_product_field($form_id, 1, "Product", "", 4);
        $field_product_id = $this->create_template_product_field($form_id, 1, "Product", "Product", $product_type="fixed", $product_value='100',$option_label=array(), $option_value=array(), "",4);
        $this->create_template_row_structure($form_id, array($field_product_id), 1, '1'); 
    }
    public function create_contact_template_c11($form_id){
        //Email 
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email address', '', 1);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');

        ////Assets Category
        $field_category_id = $this->create_template_select_field($form_id, 1, 'Asset Category', '', array('Cell Phone', 'Desktop Computer', 'Notebook Computer','Keyboard','Mouse','Monitor','Printer'), 2);
        $this->create_template_row_structure($form_id, array($field_category_id), 1, '1');
        //Descr
        $field_ass_desc_id = $this->create_template_text_field($form_id, 1, 'Asset Description', '', '',1, 1);
        $this->create_template_row_structure($form_id, array($field_ass_desc_id), 1, '1');
        //Serial 
        $field_ass_serial_id = $this->create_template_text_field($form_id, 1, 'Serial#', '', '',1, 1);
        $this->create_template_row_structure($form_id, array($field_ass_serial_id), 1, '1');
        //Modal
        $field_ass_model_id = $this->create_template_text_field($form_id, 1, 'Model#', '', '',1, 1);
        $this->create_template_row_structure($form_id, array($field_ass_model_id), 1, '1');
        //Asset Cost
        $field_ass_cost_id = $this->create_template_text_field($form_id, 1, 'Asset Cost', '', '',0, 1);
        $this->create_template_row_structure($form_id, array($field_ass_cost_id), 1, '1');

        $field_ass_condition_id = $this->create_template_text_field($form_id, 1, 'Condition', '', '',1, 1);
        $this->create_template_row_structure($form_id, array($field_ass_condition_id), 1, '1');
    }

    public function create_contact_template_c12($form_id){
        
        $field_certificate_id = $this->create_template_number_field($form_id, 1, 'Marriage Certificate number', '', '',0, 1);
        $this->create_template_row_structure($form_id, array($field_certificate_id), 1, '1');
        
        $field_place_id = $this->create_template_text_field($form_id, 1, 'Place of marriage', '', '',0, 2);
        $field_date_id = $this->create_template_calender_field($form_id,1,$label="Date of marriage", "", 0, 3);
        $this->create_template_row_structure($form_id, array($field_place_id, $field_date_id), 1, '1:1');
        
        //Email 
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', '', 4);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
        
        //=============Bridegroom Details==============//
        $this->create_template_row_structure($form_id, array(0), 1, '1', null, 'Details of the Bridegroom:');
        
        
        $field_first_id = $this->create_template_first_name_field($form_id, 1, "Bridegroom's first name", '', '',1, 5);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, "Bridegroom's last name", '', '',1, 6);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');
        
        $field_father_id = $this->create_template_text_field($form_id, 1, "Father's name", '', '',1, 7);
        $field_mother_id = $this->create_template_text_field($form_id, 1, "Mother's name", '', '',1, 8);
        $this->create_template_row_structure($form_id, array($field_father_id, $field_mother_id), 1, '1:1');
        
        //Address
        $field_address_id = $this->create_template_address_field($form_id, 1, "Address at the time of marriage", "", 9);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');
        
        $field_witness_id = $this->create_template_text_field($form_id, 1, "Witness name", '', '',0, 10);
        $this->create_template_row_structure($form_id, array($field_witness_id), 1, '1:1');
        
        
        //=============Bride Details==================//
        $this->create_template_row_structure($form_id, array(0), 1, '1', null, 'Details of the Bride:');

        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'Bride first name', '', '',1, 11);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Bride last name', '', '',1, 12);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');
        
        $field_father_id = $this->create_template_text_field($form_id, 1, "Father's name", '', '',1, 13);
        $field_mother_id = $this->create_template_text_field($form_id, 1, "Mother's name", '', '',1, 14);
        $this->create_template_row_structure($form_id, array($field_father_id, $field_mother_id), 1, '1:1');
        
        //Address
        $field_address_id = $this->create_template_address_field($form_id, 1, "Address at the time of marriage", "", 15);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');
        
        $field_witness_id = $this->create_template_text_field($form_id, 1, "Witness name", '', '',0, 16);
        $this->create_template_row_structure($form_id, array($field_witness_id), 1, '1:1');
        
        $field_certified_by_id = $this->create_template_text_field($form_id, 1, "Certified by", '', '',0, 17);
        $this->create_template_row_structure($form_id, array($field_certified_by_id), 1, '1:1');
        
    }
    public function create_contact_template_c13($form_id){
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Description", "<p>Do you know someone who'd be perfect for an open position in our company? Please fill out the following form so we can reach out</p>","",1);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        //Employee Information
        $field_first_id = $this->create_template_first_name_field($form_id, 1, "Employee's first name", 'First Name', '',1, 2);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, "Employee's last name", 'Last Name', '',1, 3);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');
        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Official email ID' , '', 4);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
        
        $field_emp_id = $this->create_template_number_field($form_id, 1, 'Employee ID', '', '',0, 5);
        $this->create_template_row_structure($form_id, array($field_emp_id), 1, '1');
        
        //Mobile
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Contact number', '',1, 6);
        $this->create_template_row_structure($form_id, array($field_mobile_id), 1, '1');

        //Candidate Information
        $field_c_first_id = $this->create_template_first_name_field($form_id, 1, "Candidate's first name", '', '',1, 7);
        $field_c_last_id = $this->create_template_last_name_field($form_id, 1, "Candidate's last name", '', '',1, 8);
        $this->create_template_row_structure($form_id, array($field_c_first_id, $field_c_last_id), 1, '1:1',null,'Referral Information:');
        //Email
        $field_c_email_id = $this->create_template_email_field($form_id, 1, 0, "Candidate's email ID" , '', 9);
        $this->create_template_row_structure($form_id, array($field_c_email_id), 1, '1');
        //Mobile
        $field_c_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Contact number', '',1, 10);
        $this->create_template_row_structure($form_id, array($field_c_mobile_id), 1, '1');
        
        //DOB
        $field_c_dob_id = $this->create_template_dob_field($form_id, 1, "Date of Birth", "",0, 11);
        $this->create_template_row_structure($form_id, array($field_c_dob_id), 1, '1');
        
        
        $field_position_id = $this->create_template_radio_field($form_id, 1, 'Position applied for', '', 13, array('Application Developer: Java FullStack','Associate Product Manager','Business Analyst','DevOps Engineer','Data Engineer: Big Data','Implementation Engineer','Marketing and Sales','Operations Specialist','Quality Analyst'));
        $this->create_template_row_structure($form_id, array($field_position_id), 1, '1');
        
        $field_location_id = $this->create_template_radio_field($form_id, 1, 'Preferred location', '', 14, array('Austin','Los Angeles','Nashville','New York','San Diego'));
        $this->create_template_row_structure($form_id, array($field_location_id), 1, '1');
        
        //Resume
        $field_file_id = $this->create_template_file_upload_field($form_id, 1, "Attach resume", "", 15, "PDF|DOCX");
        $this->create_template_row_structure($form_id, array($field_file_id), 1, '1');
        
        $field_qualification_id = $this->create_template_text_field($form_id, 1, 'Why is this candidate qualified for the position?', '', '',0, 16);
        $this->create_template_row_structure($form_id, array($field_qualification_id), 1, '1');
        
        $field_terms_id = $this->create_template_termscondition_field($form_id, 1, $label='Undertaking', '', 17, "I understand that if the candidate referred is hired as a result of my referral, I will undertake guarantee of the candidate's potential for this role. I will receive referral bonus within one month as soon as the individual completes 90 days of employment within the company.",'I accept');
        $this->create_template_row_structure($form_id, array($field_terms_id), 1, '1');
    }
    public function create_contact_template_c14($form_id){
        
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Spend Summers in the Best Summer Camp!", '<p>The complaints will be administered within 3 working days of receipt. The resolution will be provided within a week.</p>',"",1);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        
        
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 2);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 3);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');
        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 4);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
        //Mobile
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Mobile Number', '',1, 5);
        $this->create_template_row_structure($form_id, array($field_mobile_id), 1, '1');
        //Residential Address
        $field_address_id = $this->create_template_address_field($form_id, 1, "Residential Address", "", 6);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');
        
        //Accident
        $field_acc_date_id = $this->create_template_calender_field($form_id,1,$label="Date of the incident", "",1, 7);
        $field_acc_place_id = $this->create_template_text_field($form_id, 1, 'Location of incident', '', '',1, 8);
        $this->create_template_row_structure($form_id, array($field_acc_date_id,$field_acc_place_id), 1, '1:1');
        
        $field_against_id = $this->create_template_text_field($form_id, 1, 'Name of the person/organization against whom the complaint is filed', '', '',1, 9);
        $this->create_template_row_structure($form_id, array($field_against_id), 1, '1');
        // Please provide summary
        $field_msg_id = $this->create_template_textarea_field($form_id, 1, 'Please provide summary of the complaint or issue', '', '', 1, 10, 4,12);
        $this->create_template_row_structure($form_id, array($field_msg_id), 1, '1');
        
        $field_witness_id = $this->create_template_text_field($form_id, 1, 'Please provide further details regarding proof or any eye witness', '', '',0, 11);
        $this->create_template_row_structure($form_id, array($field_witness_id), 1, '1');
        
        //Would you want us to notify you on the action taken?
        $field_gender_id = $this->create_template_radio_field($form_id, 1, 'Would you want us to notify you on the action taken?', '', 12, array('Yes','No'));
        $this->create_template_row_structure($form_id, array($field_gender_id), 1, '1');
        
        // What steps should be considered to avoid repeat of this problem?
        $field_msg1_id = $this->create_template_textarea_field($form_id, 1, 'What steps should be considered to avoid repeat of this problem?', '', '', 1, 13, 4, 12);
        $this->create_template_row_structure($form_id, array($field_msg1_id), 1, '1');
    }
    public function create_contact_template_c15($form_id){
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Spend Summers in the Best Summer Camp!", '<h1 style="text-align: left"><em><strong>Join the Best Summer Camp in the States! </strong></em></h1>
        <h4>Fill out the form below to confirm your spot now!</h4>',"",1);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        
        $this->create_template_row_structure($form_id, array(0), 1, '1',null,'Personal Information');
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",2);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        //Child Information
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 3);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 4);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1',null);
        
        //Email & Contact number (if any)
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 5);
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Contact number (if any)', '',0,6);
        $this->create_template_row_structure($form_id, array($field_email_id,$field_mobile_id), 1, '1');
        
        //Address
        $field_address_id = $this->create_template_address_field($form_id, 1, "Address", "", 7);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');
        
        //Gender
        $field_gender_id = $this->create_template_radio_field($form_id, 1, 'Gender', '', 8, array('Male', 'Female', 'Other'));
        $this->create_template_row_structure($form_id, array($field_gender_id), 1, '1');
        //Age
        $field_age = $this->create_template_number_field($form_id, 1, 'What is your age?', 'Enter age in number', '',0, 9);
        $this->create_template_row_structure($form_id, array($field_age), 1, '1');
        
        //School
        $field_school_id = $this->create_template_radio_field($form_id, 1, 'You are a student at?', '', 10, array('School', 'College/ University', 'Other'));
        $this->create_template_row_structure($form_id, array($field_school_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_school_id.'_0'=>array('controlling_field'=>$field_school_id,'op'=>'==','values'=>array('School')),
                                    'c_'.$field_school_id.'_1'=>array('controlling_field'=>$field_school_id,'op'=>'==','values'=>array('College/ University'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_sch_sel_id = $this->create_template_text_field($form_id, 1, 'Name of the institution', '', '',1, 11, $conditions);
        $this->create_template_row_structure($form_id, array($field_sch_sel_id), 1, '1',null);
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_school_id.'_0'=>array('controlling_field'=>$field_school_id,'op'=>'==','values'=>array('Other'))
                                    
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_sch_other_id = $this->create_template_text_field($form_id, 1, 'Please Specify', '', '',1, 12, $conditions);
        $this->create_template_row_structure($form_id, array($field_sch_other_id), 1, '1',null);
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",13);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        //Parent/Guardian Information
        $this->create_template_row_structure($form_id, array(0), 1, '1',null,'Parent/ Guardian Information');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",14);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        
        
        $field_first_id = $this->create_template_text_field($form_id, 1, 'First Name', '', '',1, 15);
        $field_last_id = $this->create_template_text_field($form_id, 1, 'Last Name', '', '',1, 16);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1',null);
        
        $field_rel_id = $this->create_template_radio_field($form_id, 1, 'Relationship', '', 17, array('Father', 'Mother', 'Elder brother/ sister','Grandfather','Grandmother','Family relative','Guardian'));
        $this->create_template_row_structure($form_id, array($field_rel_id), 1, '1');
        
        //Mobile
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Phone', '',1, 18);
        $this->create_template_row_structure($form_id, array($field_mobile_id), 1, '1');
        
        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 19);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
        
        //Address
        $field_address_id = $this->create_template_address_field($form_id, 1, "Address", "", 20);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');
        
        $field_occ_id = $this->create_template_text_field($form_id, 1, 'Occupation', '', '',1, 21);
        $this->create_template_row_structure($form_id, array($field_occ_id), 1, '1',null);
        
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",22);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        //Emergency Contact
        $this->create_template_row_structure($form_id, array(0), 1, '1',null,'Emergency Contact');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",23);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        
        $field_first_id = $this->create_template_text_field($form_id, 1, 'First Name', '', '',1, 24);
        $field_last_id = $this->create_template_text_field($form_id, 1, 'Last Name', '', '',1, 25);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1',null,'Emergency Contact');
        //Mobile
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Phone', '',1, 26);
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 27);
        $this->create_template_row_structure($form_id, array($field_mobile_id, $field_email_id), 1, '1:1');
        // Medical Concerns
        $field_medical_id = $this->create_template_textarea_field($form_id, 1, 'Any medical concerns? ', '', '', 1, 28, 4, 12);
        $this->create_template_row_structure($form_id, array($field_medical_id), 1, '1');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",29);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        //Emergency Contact
        $this->create_template_row_structure($form_id, array(0), 1, '1',null,'Summer Camp Information');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",30);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        $field_camp_id = $this->create_template_radio_field($form_id, 1, 'Which camp would you like to join?', '', 31, array('The adventures (July 18th - 28th, 2022)', 'The Explorers (Aug 14th - 22nd, 2022)', 'The Researchers (Sep 16th - 27th, 2022)'));
        $this->create_template_row_structure($form_id, array($field_camp_id), 1, '1');
        
        $field_attend_id = $this->create_template_radio_field($form_id, 1, 'Will you be attending the camp?', '', 32, array('By yourself', 'With friends', 'With cousins','With own brothers/ sisters'));
        $this->create_template_row_structure($form_id, array($field_attend_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_attend_id.'_0'=>array('controlling_field'=>$field_attend_id,'op'=>'==','values'=>array('By yourself'))
                                    
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_attend_self_id = $this->create_template_radio_field($form_id, 1, 'Are you open to accompany other camp students with you?', '', 33, array('Yes', 'No', 'Not Sure'), $conditions);
        $this->create_template_row_structure($form_id, array($field_attend_self_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_attend_id.'_0'=>array('controlling_field'=>$field_attend_id,'op'=>'==','values'=>array('With friends')),
                                    'c_'.$field_attend_id.'_1'=>array('controlling_field'=>$field_attend_id,'op'=>'==','values'=>array('With cousins')),
                                    'c_'.$field_attend_id.'_2'=>array('controlling_field'=>$field_attend_id,'op'=>'==','values'=>array('With own brothers/ sisters'))
                                    
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_attend_num_id = $this->create_template_number_field($form_id, 1, 'How many?', '', '',1, 34, $conditions);
        $this->create_template_row_structure($form_id, array($field_attend_num_id), 1, '1');
        
        $field_camp_id = $this->create_template_radio_field($form_id, 1, 'Do you need travel assistance for reaching the camp site?', '', 35, array('Yes','No'));
        $this->create_template_row_structure($form_id, array($field_camp_id), 1, '1');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",36);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        //Emergency Contact
        $this->create_template_row_structure($form_id, array(0), 1, '1',null,'Health Declarations');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",37);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Important information", '<p>Please fill-up this form as required by the Government that will require you to declare any illness and provide information that will aid in contract tracing, should the need arise. Be sure that the information you give is accurate and complete. All the information submitted shall be encrypted, and strictly used only in compliance to law, guidelines, and ordinances, in relation to business operation in light of COVID-19 response.</p>',"",38);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        
        $field_allergies_id = $this->create_template_radio_field($form_id, 1, 'Do you or your companions have any kind of allergies?', '', 39, array('Yes','No'));
        $this->create_template_row_structure($form_id, array($field_allergies_id), 1, '1');
        $conditions = array('rules'=>array(
                                    'c_'.$field_allergies_id.'_0'=>array('controlling_field'=>$field_allergies_id,'op'=>'==','values'=>array('Yes'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_all_spec_id = $this->create_template_text_field($form_id, 1, 'Please specify', ' ', '',1, 40, $conditions);
        $this->create_template_row_structure($form_id, array($field_all_spec_id), 1, '1');
        
        $field_companions_id = $this->create_template_radio_field($form_id, 1, 'Are you or your companions taking any kind of medications that requires attention?', '', 41, array('Yes','No'));
        $this->create_template_row_structure($form_id, array($field_companions_id), 1, '1');
        $conditions = array('rules'=>array(
                                    'c_'.$field_companions_id.'_0'=>array('controlling_field'=>$field_companions_id,'op'=>'==','values'=>array('Yes'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_com_spec_id = $this->create_template_text_field($form_id, 1, 'Please specify', ' ', '',1, 42, $conditions);
        $this->create_template_row_structure($form_id, array($field_com_spec_id), 1, '1');
        
        $field_chronic_id = $this->create_template_radio_field($form_id, 1, 'Do you or your companions ave any chronic diseases?', '', 43, array('Yes','No'));
        $this->create_template_row_structure($form_id, array($field_chronic_id), 1, '1');
        $conditions = array('rules'=>array(
                                    'c_'.$field_chronic_id.'_0'=>array('controlling_field'=>$field_chronic_id,'op'=>'==','values'=>array('Yes'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_chronic_spec_id = $this->create_template_text_field($form_id, 1, 'Please specify', ' ', '',1, 44, $conditions);
        $this->create_template_row_structure($form_id, array($field_chronic_spec_id), 1, '1');
        
        $field_alcohol_id = $this->create_template_radio_field($form_id, 1, 'Are you or your companions habitual to drugs and alcohol?', '', 45, array('Yes','No'));
        $this->create_template_row_structure($form_id, array($field_alcohol_id), 1, '1');
        $conditions = array('rules'=>array(
                                    'c_'.$field_alcohol_id.'_0'=>array('controlling_field'=>$field_alcohol_id,'op'=>'==','values'=>array('Yes'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_alcohol_spec_id = $this->create_template_text_field($form_id, 1, 'Please specify', ' ', '',1, 46, $conditions);
        $this->create_template_row_structure($form_id, array($field_alcohol_spec_id), 1, '1');
        
        $field_hereditary_id = $this->create_template_checkbox_field($form_id, 1, 'Do you or your companions have any hereditary conditions/diseases?', '', 47, array('High blood pressure','Diabetes','Hemophilia', 'Thalassemia', 'Other', 'None'));
        $this->create_template_row_structure($form_id, array($field_hereditary_id), 1, '1');
        $conditions = array('rules'=>array(
                                    'c_'.$field_hereditary_id.'_0'=>array('controlling_field'=>$field_hereditary_id,'op'=>'==','values'=>array('High blood pressure')),
                                    'c_'.$field_hereditary_id.'_1'=>array('controlling_field'=>$field_hereditary_id,'op'=>'==','values'=>array('Diabetes')),
                                    'c_'.$field_hereditary_id.'_2'=>array('controlling_field'=>$field_hereditary_id,'op'=>'==','values'=>array('Hemophilia')),
                                    'c_'.$field_hereditary_id.'_3'=>array('controlling_field'=>$field_hereditary_id,'op'=>'==','values'=>array('Thalassemia')),
                                    'c_'.$field_hereditary_id.'_4'=>array('controlling_field'=>$field_hereditary_id,'op'=>'==','values'=>array('Other'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_hereditary_spec_id = $this->create_template_text_field($form_id, 1, 'Please specify', ' ', '',1, 48, $conditions);
        $this->create_template_row_structure($form_id, array($field_hereditary_spec_id), 1, '1');
        
        $this->create_template_row_structure($form_id, array(0), 1, '1',null,'Declaration and Consent');
        
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Spend Summers in the Best Summer Camp!", '<p>By signing this form, I declare that the information I have given is true, correct, and complete. I understand that failure to answer any question or giving a false answer can be penalized in accordance with the law.</p>',"",49);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        
        $field_esign_id = $this->create_template_esign_field($form_id,1,"Signature", $class="", 1, 50);
        $field_date_id = $this->create_template_calender_field($form_id,1,"Date", "",1, 51);
        $this->create_template_row_structure($form_id, array($field_esign_id, $field_date_id), 1, '1:1');
    }
    public function create_contact_template_c16($form_id){
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 1);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 2);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1',null);
        
        //Email & Contact number (if any)
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 3);
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Contact Number', '',0,4);
        $this->create_template_row_structure($form_id, array($field_email_id,$field_mobile_id), 1, '1');
        
        //DOB
        $field_dob_id = $this->create_template_dob_field($form_id, 1, "Birth Date", "",1, 5);
        $this->create_template_row_structure($form_id, array($field_dob_id), 1, '1');
        
        //Address
        $field_address_id = $this->create_template_address_field($form_id, 1, "Address", "", 6);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');
        
        //Gender
        $field_gender_id = $this->create_template_radio_field($form_id, 1, 'Gender', '', 7, array('Male', 'Female', 'Other'));
        $this->create_template_row_structure($form_id, array($field_gender_id), 1, '1');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",8);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        //Emergency Contact
        $this->create_template_row_structure($form_id, array(0), 1, '1',null,'Health Information');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",9);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        
        $field_exercise_id = $this->create_template_radio_field($form_id, 1, 'Do your exercise?', '',10, array('Yes','No'));
        $this->create_template_row_structure($form_id, array($field_exercise_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_exercise_id.'_0'=>array('controlling_field'=>$field_exercise_id,'op'=>'==','values'=>array('Yes'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_exercise_cc_id = $this->create_template_radio_field($form_id, 1, 'How often?', '',11, array('Sometimes','Once a week','2 days/ Week','3 days/ Week','4 days/ Week','5 days/ Week','6+ days/ Week'), $conditions);
        $this->create_template_row_structure($form_id, array($field_exercise_cc_id), 1, '1');
        
        //Height
        $field_height_id = $this->create_template_number_field($form_id, 1, 'Height (in cms)', '', '',0, 12);
        $this->create_template_row_structure($form_id, array($field_height_id), 1, '1');
        
        //Weight
        $field_weight_id = $this->create_template_number_field($form_id, 1, 'Weight (in kg)', '', '',0, 13);
        $this->create_template_row_structure($form_id, array($field_weight_id), 1, '1');
        
        //
        $field_current_id = $this->create_template_select_field($form_id, 1, 'How would you rate your current physical condition?', '', array('0 - Not good', '1', '2','3','4','5','6','7','8','9','10 - Very Good'), 14);
        $this->create_template_row_structure($form_id, array($field_current_id), 1, '1');
        
        $field_exercise_month_id = $this->create_template_radio_field($form_id, 1, 'Have you been exercising continuously for the last 3 months?', '',15, array('Yes','No'));
        $this->create_template_row_structure($form_id, array($field_exercise_month_id), 1, '1');
        
        $field_health_id = $this->create_template_radio_field($form_id, 1, 'Are you suffering from any health conditions?', '',16, array('Yes','No'));
        $this->create_template_row_structure($form_id, array($field_health_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_health_id.'_0'=>array('controlling_field'=>$field_health_id,'op'=>'==','values'=>array('Yes'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_health_desc_id = $this->create_template_text_field($form_id, 1, 'Please provide more details', '', '',1, 17, $conditions);
        $this->create_template_row_structure($form_id, array($field_health_desc_id), 1, '1');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",18);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        
        $field_hear_id = $this->create_template_checkbox_field($form_id, 1, 'How did you hear about us?', '', 19, array('Online','Newspaper','College/ University', 'Social Media','Word of mouth','Other'));
        $this->create_template_row_structure($form_id, array($field_hear_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_hear_id.'_0'=>array('controlling_field'=>$field_hear_id,'op'=>'==','values'=>array('Other'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_hear_desc_id = $this->create_template_text_field($form_id, 1, 'Please specify', '', '',0, 19, $conditions);
        $this->create_template_row_structure($form_id, array($field_hear_desc_id), 1, '1');
        
        
        $field_reason_id = $this->create_template_checkbox_field($form_id, 1, 'Reason for attending the boot camp?', '', 20, array('Loose Fat','Muscle development','Tone body', 'Injury rehabilitation','Nutrition diet development','Start exercising','Fun','Other'));
        $this->create_template_row_structure($form_id, array($field_reason_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_reason_id.'_0'=>array('controlling_field'=>$field_reason_id,'op'=>'==','values'=>array('Other'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_reason_desc_id = $this->create_template_text_field($form_id, 1, 'Please specify', '', '',0, 21, $conditions);
        $this->create_template_row_structure($form_id, array($field_reason_desc_id), 1, '1');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",22);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",23);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1',null,'Declaration');
        
        $field_terms_id = $this->create_template_termscondition_field($form_id, 1, $label='Terms and Conditions', '', 24, 'I agree that the information provided by me is 100% true.','I agree');
        $this->create_template_row_structure($form_id, array($field_terms_id), 1, '1');
        
        $field_name_id = $this->create_template_text_field($form_id, 1, 'Full Name', '', '',1, 25);
        $field_date_id = $this->create_template_calender_field($form_id,1,$label="Date", "",1, 26);
        $this->create_template_row_structure($form_id, array($field_name_id, $field_date_id), 1, '1:1');
    }
    public function create_contact_template_c17($form_id){
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 1);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 2);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1',null);
        
        //Email & Contact number (if any)
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 3);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
 
        //Mobile
        $field_mobile_id = $this->create_template_radio_field($form_id, 1, 'Do you wish to provide mobile number for further discussion?', '', 4, array('Yes', 'No', 'Not Sure'));
        $this->create_template_row_structure($form_id, array($field_mobile_id), 1, '1');
        $conditions = array('rules'=>array(
                                    'c_'.$field_mobile_id.'_0'=>array('controlling_field'=>$field_mobile_id,'op'=>'==','values'=>array('Yes'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_mobile_cc_id = $this->create_template_mobile_field($form_id, 1, 'Contact Number', '',0,5, $conditions);
        $this->create_template_row_structure($form_id, array($field_mobile_cc_id), 1, '1');
        
        //Gender
        $field_gender_id = $this->create_template_radio_field($form_id, 1, 'Gender', '', 6, array('Male', 'Female', 'Other'));
        $this->create_template_row_structure($form_id, array($field_gender_id), 1, '1');
        
        //Age
        $field_age_id = $this->create_template_radio_field($form_id, 1, 'Age', '', 7, array('Under 12', '12-18', '19-25','26-32','33-40','41-49','50-59','60+'));
        $this->create_template_row_structure($form_id, array($field_age_id), 1, '1');
        
        //Marital Status
        $field_marital_id = $this->create_template_radio_field($form_id, 1, 'Marital Status', '', 8, array('Single', 'Married', 'Separated','Widowed','Prefer not to disclose'));
        $this->create_template_row_structure($form_id, array($field_marital_id), 1, '1');
        
        //Marital Status
        $field_income_id = $this->create_template_radio_field($form_id, 1, 'What is your annual household income?', '', 9, array('Less than $20,999', '$21,000 - $44,999', '$45,000 - $75,999','$76,000 - $99,999','$100,000 +'));
        $this->create_template_row_structure($form_id, array($field_income_id), 1, '1');
        
        //Employment
        $field_employment_id = $this->create_template_checkbox_field($form_id, 1, 'What is your employment status?', '', 10, array('Employed (Part-time)','Employed (Full-time)','Unemployed', 'Self-employed','Student','Homemaker','Looking for a job','Prefer not to answer'));
        $this->create_template_row_structure($form_id, array($field_employment_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_employment_id.'_0'=>array('controlling_field'=>$field_employment_id,'op'=>'==','values'=>array('Employed (Part-time)')),
                                    'c_'.$field_employment_id.'_1'=>array('controlling_field'=>$field_employment_id,'op'=>'==','values'=>array('Employed (Full-time)')),
                                    'c_'.$field_employment_id.'_2'=>array('controlling_field'=>$field_employment_id,'op'=>'==','values'=>array('Self-employed'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_employment_desc_1_id = $this->create_template_text_field($form_id, 1, "Please enter your company's name", '', '',1, 11, $conditions);
        $this->create_template_row_structure($form_id, array($field_employment_desc_1_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_employment_id.'_0'=>array('controlling_field'=>$field_employment_id,'op'=>'==','values'=>array('Student'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_employment_desc_2_id = $this->create_template_text_field($form_id, 1, "Please enter School/ College/ University name's name", '', '',1, 11, $conditions);
        $this->create_template_row_structure($form_id, array($field_employment_desc_2_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_employment_id.'_0'=>array('controlling_field'=>$field_employment_id,'op'=>'==','values'=>array('Looking for a job'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        //Employment
        $field_employment_desc_3_id = $this->create_template_checkbox_field($form_id, 1, 'What kind of job are you looking for?', '', 12, array('Marketing','Sales','Operations', 'HR','Finance','Staff','Accounts'), $conditions);
        $this->create_template_row_structure($form_id, array($field_employment_desc_3_id), 1, '1');
        
        //Qualification
        $field_qualification_id = $this->create_template_radio_field($form_id, 1, 'What is your highest level of education?', '', 13, array('Less than high school', 'High school', 'Undergraduate','Post-graduate','Some College/ University','College Diploma/ Certificate','Masters Degree','Phd/ Doctorate','Other','Prefer not to share'));
        $this->create_template_row_structure($form_id, array($field_qualification_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_qualification_id.'_0'=>array('controlling_field'=>$field_qualification_id,'op'=>'==','values'=>array('High School')),
                                    'c_'.$field_qualification_id.'_1'=>array('controlling_field'=>$field_qualification_id,'op'=>'==','values'=>array('Undergraduate')),
                                    'c_'.$field_qualification_id.'_2'=>array('controlling_field'=>$field_qualification_id,'op'=>'==','values'=>array('Post-graduate')),
                                    'c_'.$field_qualification_id.'_3'=>array('controlling_field'=>$field_qualification_id,'op'=>'==','values'=>array('Some College/ University')),
                                    'c_'.$field_qualification_id.'_4'=>array('controlling_field'=>$field_qualification_id,'op'=>'==','values'=>array('College Diploma/ Certificate')),
                                    'c_'.$field_qualification_id.'_5'=>array('controlling_field'=>$field_qualification_id,'op'=>'==','values'=>array('Masters Degree')),
                                    'c_'.$field_qualification_id.'_6'=>array('controlling_field'=>$field_qualification_id,'op'=>'==','values'=>array('Phd/ Doctorate')),
                                    'c_'.$field_qualification_id.'_7'=>array('controlling_field'=>$field_qualification_id,'op'=>'==','values'=>array('Other'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_qualification_dec_1_id = $this->create_template_text_field($form_id, 1, "Please enter School/ College/ University name's name", '', '',1, 14, $conditions);
        $this->create_template_row_structure($form_id, array($field_qualification_dec_1_id), 1, '1');
        
        //Excercise
        $field_excercise_id = $this->create_template_radio_field($form_id, 1, 'Do you exercise?', '', 15, array('Yes', 'No'), $conditions);
        $this->create_template_row_structure($form_id, array($field_excercise_id), 1, '1');
        $conditions = array('rules'=>array(
                                    'c_'.$field_excercise_id.'_0'=>array('controlling_field'=>$field_excercise_id,'op'=>'==','values'=>array('Yes'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_excercise_desc_id = $this->create_template_radio_field($form_id, 1, 'How often?', '', 16, array('1 - 2 times/ week', '3 - 4 times/ week', '5 - 7 times/ week','Sometimes','Everyday','Prefer not to share'), $conditions);
        $this->create_template_row_structure($form_id, array($field_excercise_desc_id), 1, '1');
        
        //You are interested in which of the following? (Select all the applies)
        $field_interested_id = $this->create_template_checkbox_field($form_id, 1, 'You are interested in which of the following? (Select all the applies)', '', 17, array('Arts and Entertainment','Automobile','Books and Literature', 'Beauty and Fitness','Business','Computer and Electronics','Finance','Food and Drinks','Games','Hobbies and Leisure','Home and Garden','Internet and Telecom','Jobs and Education','Cryptocurrency','Law and Government','News','Online Community','Pets and Animals','Real Estate','Science','Shopping','Travel','Sports','Other'));
        $this->create_template_row_structure($form_id, array($field_interested_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_interested_id.'_0'=>array('controlling_field'=>$field_interested_id,'op'=>'==','values'=>array('Other'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_interested_desc_id = $this->create_template_text_field($form_id, 1, "Please specify", '', '',1, 17, $conditions);
        $this->create_template_row_structure($form_id, array($field_interested_desc_id), 1, '1');
        
        //Would you like to receive future communications based on your interests?
        $field_comm_id = $this->create_template_radio_field($form_id, 1, 'You are interested in which of the following? (Select all the applies)', '', 17, array('Yes','No','Not Sure'));
        $this->create_template_row_structure($form_id, array($field_comm_id), 1, '1');
    }
    public function create_contact_template_c18($form_id){
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Description", '<p>Class size is strictly limited</p><p>Please fill out the form to enroll your place</p>',"",1);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        
        //Student Details
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'Student Name', '', '',1, 2);
        $this->create_template_row_structure($form_id, array($field_first_id), 1, '1:1');
        
        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 3);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
        
        //Address
        $field_address_id = $this->create_template_address_field($form_id, 1, "Address", "", 4);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');
        
        //Contact
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Contact number', '',1, 5);
        $field_mobile_id_2 = $this->create_template_mobile_field($form_id, 1, 'Emergency contact', '',1, 6);
        $this->create_template_row_structure($form_id, array($field_mobile_id, $field_mobile_id_2), 1, '1:1');
        
        //DOB
        $field_dob_id = $this->create_template_dob_field($form_id, 1, "Date of birth", "",1, 7);
        $this->create_template_row_structure($form_id, array($field_dob_id), 1, '1');
        
        //Exp
        $field_exp_id = $this->create_template_select_field($form_id, 1, 'Number of years of dance experience you have', '', array('1', '2', '3', '4', '5', '6', '6 and above'), 8);
        $this->create_template_row_structure($form_id, array($field_exp_id), 1, '1');
        
        //Dance styles learnt
        $field_style_id = $this->create_template_text_field($form_id, 1, 'Dance styles learnt', '', '',0, 9);
        $this->create_template_row_structure($form_id, array($field_style_id), 1, '1:1');
        
        $field_repeateable_id = $this->create_template_repeatable_field($form_id, 1, "Any other student(s) joining along? If yes, please enter the name(s)", 1, 10);
        $this->create_template_row_structure($form_id, array($field_repeateable_id), 1, '1');
        
        $field_terms_id = $this->create_template_termscondition_field($form_id, 1, $label='Declaration', '', 11, 'I release dance group from all liability, injury and allow them to take photos for public shots to be used in future events.','I agree');
        $this->create_template_row_structure($form_id, array($field_terms_id), 1, '1');
    }
    public function create_contact_template_c19($form_id){
        $field_heading_id = $this->create_template_heading_field($form_id, 1, "Heading", "Invoice Request Form", "", 1);
        $this->create_template_row_structure($form_id, array($field_heading_id), 1, '1');
        
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Description", '<p>Please fill out the form below to request an invoice:</p>',"",2);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        
        //Name
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 3);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 4);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');
        
        //Company Name
        $field_company_id = $this->create_template_text_field($form_id, 1, 'Company Name', '', '',0, 5);
        $this->create_template_row_structure($form_id, array($field_company_id), 1, '1:1');
        
        //Email && Mobile
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', '', 6);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1:1');
        
        //Address
        $field_address_id = $this->create_template_address_field($form_id, 1, "Address", "", 7);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');
        
        //Contact
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Phone number', '', 0, 8);
        $this->create_template_row_structure($form_id, array($field_mobile_id), 1, '1:1');
        
        //Product
        $field_prd_id = $this->create_template_select_field($form_id, 1, 'Please select the product you want invoice for', '', array('Product 1', 'Product 2', 'Product 3'), 9);
        $this->create_template_row_structure($form_id, array($field_prd_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_prd_id.'_0'=>array('controlling_field'=>$field_prd_id,'op'=>'==','values'=>array('Product 1'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_order_date_1_id = $this->create_template_calender_field($form_id,1,"Product 1: Order date", "",1, 10, $conditions);
        $this->create_template_row_structure($form_id, array($field_order_date_1_id), 1, '1');
        
        $field_order_1_id = $this->create_template_number_field($form_id, 1, 'Product 1: Order number', '', '',1, 11, $conditions);
        $this->create_template_row_structure($form_id, array($field_order_1_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_prd_id.'_0'=>array('controlling_field'=>$field_prd_id,'op'=>'==','values'=>array('Product 2'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_order_date_2_id = $this->create_template_calender_field($form_id,1,"Product 2: Order date", "",1, 12, $conditions);
        $this->create_template_row_structure($form_id, array($field_order_date_2_id), 1, '1');
        
        $field_order_2_id = $this->create_template_number_field($form_id, 1, 'Product 2: Order number', '', '',1, 13, $conditions);
        $this->create_template_row_structure($form_id, array($field_order_2_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_prd_id.'_0'=>array('controlling_field'=>$field_prd_id,'op'=>'==','values'=>array('Product 3'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        $field_order_date_3_id = $this->create_template_calender_field($form_id,1,"Product 3: Order date", "",1, 14, $conditions);
        $this->create_template_row_structure($form_id, array($field_order_date_3_id), 1, '1');
        
        $field_order_3_id = $this->create_template_number_field($form_id, 1, 'Product 3: Order number', '', '',1, 15, $conditions);
        $this->create_template_row_structure($form_id, array($field_order_3_id), 1, '1');
        
        
        $field_further_id = $this->create_template_text_field($form_id, 1, 'Any further information you wish to add', '', '',0, 16);
        $this->create_template_row_structure($form_id, array($field_further_id), 1, '1');
    }
    public function create_contact_template_cp1($form_id){
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 1);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 2);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');

        //Email && Mobile
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', '', 3);
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Phone', '',1, 4);
        $this->create_template_row_structure($form_id, array($field_email_id, $field_mobile_id), 1, '1:1');


        $field_address_id = $this->create_template_address_field($form_id, 1, "Address", "", 5);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');

        //Page 2
        $field_product_id = $this->create_template_product_field($form_id, 2, "Select Product", "Product", $product_type="multisel", $product_value='',$option_label=array('T-Shirt','Trousers','Shirts','Jackets','Vests','Socks'), $option_value=array(14,15,15,16,20,10), "",6);
        //$field_product_id = $this->create_template_product_field($form_id, 2, "Product", "", 1);
        $this->create_template_row_structure($form_id, array($field_product_id), 2, '1');     
        
        $field_product_fixed_id = $this->create_template_product_field($form_id, 2, "Apparel", "Wallet", $product_type="fixed", $product_value='3.40',$option_label=array(), $option_value=array(), "",7);
        $this->create_template_row_structure($form_id, array($field_product_fixed_id), 2, '1'); 
        
        $field_price_id = $this->create_template_price_field($form_id, $page=2, $label="Total Price",8);
        $this->create_template_row_structure($form_id, array($field_price_id), 2, '1'); 
    }
    
    public function create_contact_template_cp2($form_id){
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Education", "<h3>Personal Information</h3>","",1);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        //Username
        $field_user_id = $this->create_default_username_field($form_id, 1, 'Username', '', '', 4, 70, '' );
        $this->create_template_row_structure($form_id, array($field_user_id), 1, '1');
        //Field
        $field_password_id = $this->create_default_password_field($form_id, 1, 'Password', '', '', 1);
        $this->create_template_row_structure($form_id, array($field_password_id), 1, '1');
        
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '', 1,2);
        $this->create_template_row_structure($form_id, array($field_first_id), 1, '1',null,'');
        
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 3);
        $this->create_template_row_structure($form_id, array($field_last_id), 1, '1',null,'');
        
        //Email 
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', '',4);
        $this->create_template_row_structure($form_id, array($field_email_id,), 1, '1');
        
        //Mobile
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Mobile Number', '',1, 5);
        $this->create_template_row_structure($form_id, array($field_mobile_id), 1, '1');
        
        //Address
        $field_address_id = $this->create_template_address_field($form_id, 1, "Address", "", 6);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",7);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Education", "<h3>Education</h3>","",8);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
            
        $qualifications = array(
            'Undergraduate',
            'Postgraduate',
            'Masters',
            'PhD',
            'Diploma',
            'Some college degree',
            'No degree acquired' 
        );
        $field_education_id = $this->create_template_radio_field($form_id, 1, 'What is your highest education?', '', 9, $qualifications);
        $this->create_template_row_structure($form_id, array($field_education_id), 1, '1', null, '');
        //
        $field_college_id = $this->create_template_text_field($form_id, 1, 'Please enter College/ University name', '', '',0, 10);
        $this->create_template_row_structure($form_id, array($field_college_id), 1, '1');
        
        $field_degree_id = $this->create_template_text_field($form_id, 1, 'Degree name', '', '',0, 11);
        $this->create_template_row_structure($form_id, array($field_degree_id), 1, '1');
        
        $field_percentage_id = $this->create_template_text_field($form_id, 1, 'Percentage achieved (%)', '', '',0, 12);
        $this->create_template_row_structure($form_id, array($field_percentage_id), 1, '1');
        
        $field_completion_id = $this->create_template_text_field($form_id, 1, 'Year completed', '', '',0, 13);
        $this->create_template_row_structure($form_id, array($field_completion_id), 1, '1');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",14);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Recent Employment", "<h3>Recent Employment Details</h3>","",15);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        
        $field_exp_id = $this->create_template_radio_field($form_id, 1, 'Do you have any work experience?', '', 16, array('Yes','No'));
        $this->create_template_row_structure($form_id, array($field_exp_id), 1, '1', null, '');
        
        $field_exp_year_id = $this->create_template_number_field($form_id, 1, 'How many years of relevant work experience?', '', '',0, 17);
        $this->create_template_row_structure($form_id, array($field_exp_year_id), 1, '1', null, '');
        
        $field_roles_resp_id = $this->create_template_textarea_field($form_id, 1, 'Please mention your roles and responsibilities', '', '', 0, 18, 4, 12);
        $this->create_template_row_structure($form_id, array($field_roles_resp_id), 1, '1');
        
        $field_employer_id = $this->create_template_text_field($form_id, 1, 'Most recent employer name?', '', '',0, 19);
        $this->create_template_row_structure($form_id, array($field_employer_id), 1, '1');
        
        $field_emp_start_id = $this->create_template_calender_field($form_id,1,$label="Start date?", "",1, 20);
        $field_emp_end_id = $this->create_template_calender_field($form_id,1,$label="End date?", "",1, 21);
        $this->create_template_row_structure($form_id, array($field_emp_start_id,$field_emp_end_id), 1, '1:1');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",22);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Skills", "<h3>Skill Analysis</h3>","",23);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        
        $field_skills_id = $this->create_template_textarea_field($form_id, 1, 'Please enter your skills that are related to the current position', '', '', 0, 18, 4, 24);
        $this->create_template_row_structure($form_id, array($field_skills_id), 1, '1');
        
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",25);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Upload", "<h3>Upload Attachments</h3>","",26);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        
        //Resume
        $field_file_id = $this->create_template_file_upload_field($form_id, 1, "Upload CV", "", 28, "PDF|DOCX");
        $this->create_template_row_structure($form_id, array($field_file_id), 1, '1');   
        $field_cover_id = $this->create_template_file_upload_field($form_id, 1, "Upload Cover Letter", "", 29, "PDF|DOCX");
        $this->create_template_row_structure($form_id, array($field_cover_id), 1, '1');   
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Note", "<p><em>Note: If your CV is not attached/ uploaded, your application will be considered incomplete. </em></p>","",30);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        //declaration
        $field_div_id = $this->create_template_divider_field($form_id, $page=1, "Divider","",31);
        $this->create_template_row_structure($form_id, array($field_div_id), 1, '1');
        
        $field_desc_id = $this->create_template_richtext_field($form_id, $page=1, "Upload", "<h3>Declaration</h3>","",32);
        $this->create_template_row_structure($form_id, array($field_desc_id), 1, '1');
        
        $field_terms_id = $this->create_template_termscondition_field($form_id, 1, $label='Self Declaration', '', 33, '1. I understand that all the information provided in this job application form is true to my knowledge. 
2. I understand that HR reserves all the right to ask for more information or documents wherever required.','I agree');
        $this->create_template_row_structure($form_id, array($field_terms_id), 1, '1');
        
        $field_dec_name_id = $this->create_template_text_field($form_id, 1, 'Enter Full Name', '', '',0, 34);
        $this->create_template_row_structure($form_id, array($field_dec_name_id), 1, '1');
        
    }
    
    public function create_contact_template_cp2_old($form_id){
        
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '', 1,1);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 2);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1',null,'Personal Information');
        
        //Address
        $field_address_id = $this->create_template_address_field($form_id, 1, "Address", "", 3);
        $this->create_template_row_structure($form_id, array($field_address_id), 1, '1');
        
        //Email Mobile
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email', '',4);
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Phone', '',1, 5);
        $this->create_template_row_structure($form_id, array($field_email_id,$field_mobile_id), 1, '1:1');
        
        //DOB
        $field_dob_id = $this->create_template_dob_field($form_id, 1, "Date of birth", "",0, 6);
        $field_citizenship_id = $this->create_template_text_field($form_id, 1, 'Citizenship', '', '',0, 7);
        $this->create_template_row_structure($form_id, array($field_dob_id,$field_citizenship_id), 1, '1:1');
        
        $field_linkedin_id = $this->create_template_social_field($form_id, 1, "Linked", "LinkedIn link", "",0, 8);
        $this->create_template_row_structure($form_id, array($field_linkedin_id), 1, '1');   
        
        $field_position_id = $this->create_template_select_field($form_id, 1, 'Position applied for', '', array('Option 1', 'Option 2', 'Option 3'), 9);
        $this->create_template_row_structure($form_id, array($field_position_id), 1, '1');
        
        $field_place_id = $this->create_template_radio_field($form_id, 1, 'Are you legally eligible to work in Australia?', '', 10, array('Yes', 'No'));
        $this->create_template_row_structure($form_id, array($field_place_id), 1, '1', null, 'EMPLOYMENT ELIGIBILITY');
        
        $field_notice_id = $this->create_template_text_field($form_id, 1, 'What is your notice period with your current company?', '', '',0, 11);
        $this->create_template_row_structure($form_id, array($field_notice_id), 1, '1');
        
        $field_felony_id = $this->create_template_radio_field($form_id, 1, 'Have you ever been convicted of a felony?', '', 12, array('Yes', 'No'));
        $this->create_template_row_structure($form_id, array($field_felony_id), 1, '1');
        
        $conditions = array('rules'=>array(
                                    'c_'.$field_felony_id.'_0'=>array('controlling_field'=>$field_felony_id,'op'=>'==','values'=>array('Yes'))
                                    ),
                            'settings' => array('combinator'=>'OR')            
                        );
        
        $field_felony_1_id = $this->create_template_text_field($form_id, 1, 'If yes, please elaborate', '', '',1, 13, $conditions);
        $this->create_template_row_structure($form_id, array($field_felony_1_id), 1, '1');
        
        //EDUCATION details
        //HighSchool
        $field_highschool_id = $this->create_template_text_field($form_id, 1, 'What is your notice period with your current company?', '', '',0, 14);
        $this->create_template_row_structure($form_id, array($field_highschool_id), 1, '1',null, 'High School');
        
        $field_highschool_state_id = $this->create_template_text_field($form_id, 1, 'State, City', '', '',0, 15);
        $field_highschool_year_id = $this->create_template_text_field($form_id, 1, 'Year of completion', '', '',0, 16);
        $this->create_template_row_structure($form_id, array($field_highschool_state_id,$field_highschool_year_id), 1, '1:1');
        
        //College
        $field_college_id = $this->create_template_text_field($form_id, 1, 'College/University', '', '',0, 17);
        $this->create_template_row_structure($form_id, array($field_college_id), 1, '1');
        
        $field_college_state_id = $this->create_template_text_field($form_id, 1, 'State, City', '', '',0, 18);
        $field_college_year_id = $this->create_template_text_field($form_id, 1, 'Year of completion', '', '',0, 19);
        $this->create_template_row_structure($form_id, array($field_college_state_id,$field_college_year_id), 1, '1:1');
        
        //Other
        $field_edu_other_id = $this->create_template_text_field($form_id, 1, 'Other', '', '',0, 20);
        $this->create_template_row_structure($form_id, array($field_edu_other_id), 1, '1');
        
        $field_edu_other_state_id = $this->create_template_text_field($form_id, 1, 'State, City', '', '',0, 21);
        $field_edu_other_year_id = $this->create_template_text_field($form_id, 1, 'Year of completion', '', '',0, 22);
        $this->create_template_row_structure($form_id, array($field_edu_other_state_id,$field_edu_other_year_id), 1, '1:1');
        
        
        //PREVIOUS EMPLOYMENT
        $field_employer_id = $this->create_template_text_field($form_id, 1, 'Current employer (company/Individual)', '', '',0, 23);
        $this->create_template_row_structure($form_id, array($field_employer_id), 1, '1',null, 'PREVIOUS EMPLOYMENT');
        
        $field_employer_address_id = $this->create_template_text_field($form_id, 1, 'Address', '', '',0, 24);
        $this->create_template_row_structure($form_id, array($field_employer_address_id), 1, '1');
        
        $field_job_title_id = $this->create_template_text_field($form_id, 1, 'Job title', '', '',0, 25);
        $field_job_package_id = $this->create_template_number_field($form_id, 1, 'Yearly package', '', '',0, 26);
        $this->create_template_row_structure($form_id, array($field_job_title_id,$field_job_package_id), 1, '1:1');
        
        $field_employer_res_id = $this->create_template_text_field($form_id, 1, 'Job responsibilities', '', '',0, 27);
        $this->create_template_row_structure($form_id, array($field_employer_res_id), 1, '1');
        
        $field_msg_id = $this->create_template_textarea_field($form_id, 1, 'Any previous employment record', '', '', 0, 28, 4, 12);
        $this->create_template_row_structure($form_id, array($field_msg_id), 1, '1');
        
        //REFRENCES
        $field_refrence_id = $this->create_template_text_field($form_id, 1, 'Your reference within this company', '', '',0, 29);
        $this->create_template_row_structure($form_id, array($field_refrence_id), 1, '1',null, 'REFRENCES');
        
        $field_refrence_eid_id = $this->create_template_text_field($form_id, 1, 'Employee ID', '', '',0, 30);
        $this->create_template_row_structure($form_id, array($field_refrence_eid_id), 1, '1');
        
        //Resume
        $field_file_id = $this->create_template_file_upload_field($form_id, 1, "Cover Letter, Resume, & References", "", 31, "PDF|DOCX");
        $this->create_template_row_structure($form_id, array($field_file_id), 1, '1');   
        
        $field_terms_id = $this->create_template_termscondition_field($form_id, 1, $label='Disclaimer', '', 32, 'I certify the information above is true to the best of my knowledge and any misleading/ false information in this form may result in job being terminated.','I accept');
        $this->create_template_row_structure($form_id, array($field_terms_id), 1, '1');
    }
    public function create_contact_template_cp3($form_id){
        //Current Owner Details
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 1);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 2);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1',null,'Current Owner Details');
        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 3);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
        //Mobile
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Phone', '',1, 4);
        $this->create_template_row_structure($form_id, array($field_mobile_id), 1, '1');
        // Transfer Reason
        $field_reason_id = $this->create_template_textarea_field($form_id, 1, 'Transfer Reason', '', '', 1, 4, 4, 12);
        $this->create_template_row_structure($form_id, array($field_reason_id), 1, '1');
        //Owner ESign
        $field_owner_esign_id = $this->create_template_esign_field($form_id,1,"E-Sign", $class="", 0, 7);
        $this->create_template_row_structure($form_id, array($field_owner_esign_id), 1, '1');


        //New Owner Details
        $field_first_id = $this->create_template_text_field($form_id, 1, 'First Name', '', '',1, 8);
        $field_last_id = $this->create_template_text_field($form_id, 1, 'Last Name', '', '',1, 9);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1',null,'New Owner Details');
        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 10);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
        //Mobile
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Phone', '',1, 11);
        $this->create_template_row_structure($form_id, array($field_mobile_id), 1, '1');
        //New Owner ESign
        $field_owner_esign_id = $this->create_template_esign_field($form_id,1,"E-Sign", $class="", 0, 12);
        $this->create_template_row_structure($form_id, array($field_owner_esign_id), 1, '1');

        //Pet Details
        $field_petname_id = $this->create_template_text_field($form_id, 1, 'Name', '', '',1, 13);
        $this->create_template_row_structure($form_id, array($field_petname_id), 1, '1',null,'Pet Details');
        //Pet Type
        $field_pettype_id = $this->create_template_select_field($form_id, 1, 'Pet Type', '', array('Dog', 'Cat', 'Bird', 'Rodent', 'Reptile'), 14);
        $this->create_template_row_structure($form_id, array($field_pettype_id), 1, '1');

        //Gate on Shots
        $field_date_id = $this->create_template_radio_field($form_id, 1, 'Up to Date on Shots', '', 15, array('Yes', 'No', 'Not Sure'));
        $this->create_template_row_structure($form_id, array($field_date_id), 1, '1');
        //Spayed/Nuetered
        $field_nuetered_id = $this->create_template_radio_field($form_id, 1, 'Spayed/Nuetered', '', 16, array('Yes', 'No', 'Not Sure'));
        $this->create_template_row_structure($form_id, array($field_nuetered_id), 1, '1');
        //Gate on Shots
        $field_gdate_id = $this->create_template_radio_field($form_id, 1, 'Microchipped', '', 17, array('Yes', 'No', 'Not Sure'));
        $this->create_template_row_structure($form_id, array($field_gdate_id), 1, '1');

    }
    public function create_contact_template_cp4($form_id){
        //Child Details
        $field_first_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 1);
        $field_last_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 2);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 1, '1:1');
        
        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 3);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');
        
        //Gender
        $field_gender_id = $this->create_template_radio_field($form_id, 1, 'Gender', '', 4, array('Male', 'Female', 'Other'));
        $this->create_template_row_structure($form_id, array($field_gender_id), 1, '1');
        //DOB
        $field_dob_id = $this->create_template_dob_field($form_id, 1, "Date Of Birth", "",1, 5);
        $this->create_template_row_structure($form_id, array($field_dob_id), 1, '1');
        //Previous School
        $field_school_id = $this->create_template_text_field($form_id, 1, 'Current School', '', '',1, 6);
        $this->create_template_row_structure($form_id, array($field_school_id), 1, '1');

        //New Class
        $field_class_id = $this->create_template_text_field($form_id, 1, 'Standard in which you to want to take registration?', '', '',1, 7);
        $this->create_template_row_structure($form_id, array($field_class_id), 1, '1');

        //Parent/Guardian Information
        $field_first_id = $this->create_template_text_field($form_id, 2, 'First Name', '', '',1, 1);
        $field_last_id = $this->create_template_text_field($form_id, 2, 'Last Name', '', '',1, 1);
        $this->create_template_row_structure($form_id, array($field_first_id, $field_last_id), 2, '1:1');
        
        //Mobile
        $field_mobile_id = $this->create_template_mobile_field($form_id, 2, 'Phone', '',1, 3);
        $this->create_template_row_structure($form_id, array($field_mobile_id), 2, '1');
        //Address
        $field_address_id = $this->create_template_address_field($form_id, 2, "Address", "", 4);
        $this->create_template_row_structure($form_id, array($field_address_id), 2, '1');


    }
    public function create_registration_template_r0($form_id){
        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 3);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');

        //Username
        $field_user_id = $this->create_default_username_field($form_id, 1, 'Username', '', '', 4, 70, '' );
        $this->create_template_row_structure($form_id, array($field_user_id), 1, '1');
        //Field
        $field_password_id = $this->create_default_password_field($form_id, 1, 'Password', 'Set Your Password', '', 9);
        $this->create_template_row_structure($form_id, array($field_password_id), 1, '1');
    }
    public function create_registration_template_r1($form_id){
        
        //Username
        $field_user_id = $this->create_default_username_field($form_id, 1, 'Username', 'Select a username', 'rm-reg-username', 4, 70, 'This username has already been taken. Please try something different.' );
        $this->create_template_row_structure($form_id, array($field_user_id), 1, '1');

        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 3);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');

        //Field
        $field_password_id = $this->create_default_password_field($form_id, 1, 'Password', 'Set Your Password', 'rm-reg-password', 9);
        $this->create_template_row_structure($form_id, array($field_password_id), 1, '1');
    }
    public function create_registration_template_r2($form_id){
        //Name
        $first_name_id = $this->create_template_first_name_field($form_id, 1, 'First Name', 'Enter First Name', '',1, 1, 70, 1);
        $last_name_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', 'Enter Last Name', '',1, 2, 70, 1);
        $field_ids = array($first_name_id, $last_name_id);
        $this->create_template_row_structure($form_id, $field_ids, 1, '1:1');

        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 3);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');  
        //Username
        $field_user_id = $this->create_default_username_field($form_id, 1, 'Username', 'Select a username', 'rm-reg-username', 4, 70, 'This username has already been taken. Please try something different.' );
        $this->create_template_row_structure($form_id, array($field_user_id), 1, '1'); 

        //Field
        $field_password_id = $this->create_default_password_field($form_id, 1, 'Password', 'Set Your Password', 'rm-reg-password', 9);
        $this->create_template_row_structure($form_id, array($field_password_id), 1, '1');
    }
    public function create_registration_template_r3($form_id){
        //Name
        $first_name_id = $this->create_template_first_name_field($form_id, 1, 'First Name', 'Enter First Name', '',1, 1, 70, 1);
        $last_name_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', 'Enter Last Name', '',1, 2, 70, 1);
        $field_ids = array($first_name_id, $last_name_id);
        $this->create_template_row_structure($form_id, $field_ids, 1, '1:1');

        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 3);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');

        //Username
        $field_user_id = $this->create_default_username_field($form_id, 1, 'Username', '', '', 4, 70, '' );
        $this->create_template_row_structure($form_id, array($field_user_id), 1, '1');

        //Gender
        $field_gender_id = $this->create_template_radio_field($form_id, 1, 'Gender', 'rm-reg-radio', 5, array('Male', 'Female', 'Other'));
        $this->create_template_row_structure($form_id, array($field_gender_id), 1, '1');

        //Country
        $field_country_id = $this->create_template_country_field($form_id, 1, 'Choose', '', 6);
        $this->create_template_row_structure($form_id, array($field_country_id), 1, '1');

        //City
        $field_city_id = $this->create_template_text_field($form_id, 1, 'City', ' ', '',1, 7);
        $this->create_template_row_structure($form_id, array($field_city_id), 1, '1');

        //Mobile
        $field_mobile_id = $this->create_template_mobile_field($form_id, 1, 'Phone', '',1, 8);
        $this->create_template_row_structure($form_id, array($field_mobile_id), 1, '1');

        //Field
        $field_password_id = $this->create_default_password_field($form_id, 1, 'Password', 'Set Your Password', '', 9);
        $this->create_template_row_structure($form_id, array($field_password_id), 1, '1');

        $field_privacy_id = $this->create_template_privacy_field($form_id, 1, 'Terms & Conditions', '', 10, 'I accept terms & conditions');
        $this->create_template_row_structure($form_id, array($field_privacy_id), 1, '1');
    }


    public function create_registration_template_rp1($form_id){
        //Name
        $first_name_id = $this->create_template_first_name_field($form_id, 1, 'First Name', '', '',1, 1, 70, 1);
        $last_name_id = $this->create_template_last_name_field($form_id, 1, 'Last Name', '', '',1, 2, 70, 1);
        $field_ids = array($first_name_id, $last_name_id);
        $this->create_template_row_structure($form_id, $field_ids, 1, '1:1');
        //Gender
        $field_gender_id = $this->create_template_radio_field($form_id, 1, 'Gender', '', 5, array('Male', 'Female', 'Other'));
        $this->create_template_row_structure($form_id, array($field_gender_id), 1, '1');
        //DOB
        $field_dob_id = $this->create_template_dob_field($form_id, 1, "Date Of Birth", "",1, 6);
        $this->create_template_row_structure($form_id, array($field_dob_id), 1, '1');
        //Email
        $field_email_id = $this->create_template_email_field($form_id, 1, 0, 'Email' , '', 0);
        $this->create_template_row_structure($form_id, array($field_email_id), 1, '1');

        //Mobile
        $field_mobile_id = $this->create_template_mobile_field($form_id, 2, 'Phone', '',1, 1);
        $this->create_template_row_structure($form_id, array($field_mobile_id), 2, '1');

        //Username
        $field_user_id = $this->create_default_username_field($form_id, 2, 'Username', '', '', 2, 70);
        $this->create_template_row_structure($form_id, array($field_user_id), 2, '1');

        //Field
        $field_password_id = $this->create_default_password_field($form_id, 2, 'Password', '', '', 3);
        $this->create_template_row_structure($form_id, array($field_password_id), 2, '1');

        //Address
        $field_address_id = $this->create_template_address_field($form_id, 3, "Address", "", 1);
        $this->create_template_row_structure($form_id, array($field_address_id), 3, '1');
        $field_privacy_id = $this->create_template_privacy_field($form_id, 3, 'Terms & Conditions', '', 6, 'I accept terms & conditions');
        $this->create_template_row_structure($form_id, array($field_privacy_id), 3, '1');
    }
    //Email Field
    public function create_template_email_field($form_id, $page_no=1, $delete=0 ,$field_label='Email', $placeholder='', $order=1) {
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Email',
            'page_no' => $page_no,
            'is_deletion_allowed'=>$delete,
            'field_label' => $field_label,
            'field_placeholder' => $placeholder,
            'field_css_class' => '',
            'field_is_required' => 1,
            'field_show_on_user_page' => 0,
            'field_order'=>$order,
            'field_is_editable' => 0,
            'is_field_primary' => 1,
            'en_confirm_email'=>0,
            'email_mismatch_err'=>RM_UI_Strings::get("ERR_EMAIL_MISMATCH")
            ));

        return $field->insert_into_db();
    }


    //Text Field
    public function create_template_text_field($form_id, $page_no=1, $label='Name', $placeholder='', $class='',$required=0, $order=1, $conditions = array()) {
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Textbox',
            'page_no' => $page_no,
            'field_label' => $label,
            'field_placeholder' => $placeholder,
            'field_css_class' => $class,
            'field_is_required' => $required,
            'field_show_on_user_page' => 0,
            'field_order'=>$order,
            'field_is_editable' => 0,
            'field_is_read_only' => 0,
            'conditions'=>$conditions
            ));

        return $field->insert_into_db();
    }
    //Text Field
    public function create_template_number_field($form_id, $page_no=1, $label='Number', $placeholder='', $class='',$required=0, $order=1, $conditions = array()) {
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Number',
            'page_no' => $page_no,
            'field_label' => $label,
            'field_placeholder' => $placeholder,
            'field_css_class' => $class,
            'field_is_required' => $required,
            'field_order'=>$order,
            'conditions'=>$conditions
            ));

        return $field->insert_into_db();
    }
    public function create_template_textarea_field($form_id, $page=1, $label='Description', $placeholder='', $class='',$required=0, $order=1, $rows=4, $columns=12) {
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Textarea',
            'page_no' => $page,
            'field_label' => $label,
            'field_placeholder' => $placeholder,
            'field_is_required' => $required,
            'field_show_on_user_page' => 0,
            'field_order'=> $order,
            'field_is_editable' =>  0,
            'field_textarea_rows'=> $rows,
            'field_textarea_columns'=> $columns,
            'field_css_class' => $class
            ));

        return $field->insert_into_db();
    }

    public function create_default_password_field($form_id, $page=1, $label='Password', $placeholder='', $class='',$order=-1) {
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'UserPassword',
            'page_no' => $page,
            'is_deletion_allowed'=>1,
            'field_label' => $label,
            'field_placeholder' => $placeholder,
            'field_css_class' => $class,
            'field_is_required' => 1,
            'field_show_on_user_page' => 0,
            'field_is_editable' => 0,
            'field_is_read_only' => 0,
            'is_field_primary' => 1,
            'field_order'=>$order,
            'en_confirm_pwd'=>array(1),
            'pass_mismatch_err'=>'Your passwords do not match. Please check again.',
            'en_pass_strength'=>array(1),
            'pwd_strength_type'=>array(1),
            'pwd_short_msg'=>'Too Short',
            'pwd_weak_msg'=>'Weak',
            'pwd_medium_msg'=>'Medium',
            'pwd_strong_msg'=>'Strong',
            'help_text'=>'Password must be at least 7 characters long.'));

        return $field->insert_into_db();
    }

   public function create_default_username_field($form_id, $page=1, $label='Username', $placeholder='Select a username', $class='', $order=-2, $max_length=70, $exists_error='This username has already been taken. Please try something different.' ) {

        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Username',
            'page_no' => $page,
            'is_deletion_allowed'=>0,
            'field_label' => $label,
            'field_placeholder' => $placeholder,
            'field_css_class' => $class,
            'field_is_required' => 1,
            'field_show_on_user_page' => 1,
            'field_order'=>$order,
            'field_is_read_only' => 0,
            'field_is_editable' => 0,
            'field_max_length' => $max_length,
            'is_field_primary' => 1,
            'user_exists_error'=> $exists_error,
            'username_characters'=>array('alphabets','numbers','underscores','periods'),
            'invalid_username_format'=>'Invalid username format. Only {{allowed_characters}} allowed'));

        return $field->insert_into_db();
    }

    public function create_template_first_name_field($form_id, $page=1, $label='First Name', $placeholder='', $class='',$required=0, $order=1){
    	$field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Fname',
            'page_no' => $page,
            'is_deletion_allowed'=>0,
            'field_label' => $label,
            'field_placeholder' => $placeholder,
            'field_css_class' => $class,
            'field_is_required' => $required,
            'field_show_on_user_page' => 1,
            'field_order'=>$order,
            'field_max_length' => 70
        ));
        return $field->insert_into_db();
    }

    public function create_template_last_name_field($form_id, $page=1, $label='Last Name', $placeholder='', $class='',$required=0, $order=1){
    	$field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Lname',
            'page_no' => $page,
            'field_label' => $label,
            'field_placeholder' => $placeholder,
            'field_css_class' => $class,
            'field_is_required' => 1,
            'field_show_on_user_page' => 1,
            'field_order'=>$order,
            'field_max_length' => 70
        ));
        return $field->insert_into_db();
    }

    public function create_template_radio_field($form_id, $page=1, $label='Choose', $class='', $order=2, $value=array('option 1', 'option 2'), $conditions=array()){
    	$field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Radio',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_is_required' => '',
            'field_show_on_user_page' => 1,
            'field_order'=>$order,
            'field_value'=>$value,
            'conditions'=>$conditions
        ));
        return $field->insert_into_db();
    }
    public function create_template_checkbox_field($form_id, $page=1, $label='Choose', $class='', $order=2, $value=array('option 1', 'option 2'), $conditions=array()){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Checkbox',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_is_required' => '',
            'field_show_on_user_page' => 1,
            'field_order'=>$order,
            'field_value'=>$value,
            'conditions'=>$conditions
        ));
        return $field->insert_into_db();
    }
    public function create_template_country_field($form_id, $page=1, $label='Choose', $class='', $order=2){
    	$field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Country',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_is_required' => '',
            'field_show_on_user_page' => 1,
            'field_order'=>$order
        ));
        return $field->insert_into_db();
    }

    public function create_template_termscondition_field($form_id, $page=1, $label='Terms & Conditions', $class='', $order=2, $value='',$checkbox_label=''){
    	$field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Terms',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_is_required' => 1,
            'field_show_on_user_page' => 1,
            'field_order'=>$order,
            'field_value'=>$value,
            'tnc_cb_label'=>$checkbox_label
        ));
        return $field->insert_into_db();
    }

    public function create_template_privacy_field($form_id, $page=1, $label='Terms & Conditions', $class='', $order=2, $checkbox_label=''){
    	$field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Privacy',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_order'=>$order,
            'privacy_display_checkbox'=>1,
            'privacy_policy_content'=>$checkbox_label
        ));
        return $field->insert_into_db();
    }

    public function create_template_mobile_field($form_id, $page=1, $label='Phone', $class='', $required=0, $order=1, $conditions=array()){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Mobile',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_order'=>$order,
            'format_type'=>'international',
            'field_is_required'=> $required,
            'conditions'=>$conditions
        ));
        return $field->insert_into_db();
    }

    public function create_template_select_field($form_id, $page=1, $label='Select', $class='',$value=array('Option 1'), $order=1, $conditions=array()){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Select',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_order'=>$order,
            'field_value'=>implode(',',$value),
            'field_is_required'=> '',
            'conditions'=>$conditions
        ));
        return $field->insert_into_db();
    }

    public function create_template_row_structure($form_id, $field_ids=array(), $page_no=1, $column='1',$row_order=null, $heading="", $sub_heading="") {
        $row = new RM_Rows;
        $row_order = RM_DBManager::get_row_count_by_form_id($form_id, 1);
        $row->set(array('form_id' => $form_id,
            'page_no' => $page_no,
            'row_columns' => $column,
            'row_class' => '',
            'row_gutter' => 10,
            'row_bmargin' => 0,
            'row_width' => 0,
            'row_heading'=> $heading,
            'row_subheading' => $sub_heading,
            'field_ids' => $field_ids,
            'row_order' => $row_order,
            'row_options' => $row->get_row_options()));

        return $row->insert_into_db();
    }

    public function create_template_address_field($form_id, $page=1, $label="Address", $class="", $order=1){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Address',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_order'=>$order,
            'field_is_required'=> 1,
            'field_options'=>$field->get_field_options(),
            'field_address_type'=>'ca',
            'ca_state_type'=>'all',
            'field_ca_address1_label'=>'Address Line 1',
            'field_ca_address2_label'=>'Address Line 2',
            'field_ca_city_label'=>'City',
            'field_ca_state_label'=>'State or Region',
            'field_ca_country_label'=>'Country',
            'field_ca_zip_label'=>'ZIP',
            'field_ca_city_en'=>'City',
            'field_ca_state_en'=>'State or Region',
            'field_ca_zip_en'=>'ZIP',
            'field_ca_country_en'=>'Country',
            'field_ca_address1_en'=>'Address Line 1',
            'field_ca_address2_en'=>'Address Line 2',
            'field_ca_address1_req'=>1,
            'field_ca_city_req'=>1,
            'field_ca_zip_req'=>1,
            'field_ca_country_req'=>1,
            'field_ca_state_req'=>1,
        ));
        return $field->insert_into_db();   
    }

    public function create_template_product_field($form_id, $page=1, $label="Product", $product_name="Product", $product_type="fixed", $product_value='100',$option_label=array(), $option_value=array(), $class="", $order=1){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Price',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_order'=>$order,
            'field_is_required'=> 1,
            'field_value'=>$this->create_dummy_product($product_name, $product_type, $product_value, $option_label, $option_value)
        ));
        return $field->insert_into_db();   
    }

    public function create_template_heading_field($form_id, $page=1, $label="Heading", $heading="Heading...", $class="", $order=1){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'HTMLH',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_order'=>$order,
            'field_is_required'=> 1,
            'field_value'=>$heading,
        ));
        return $field->insert_into_db();   
    }

    public function create_template_social_field($form_id, $page=1, $type="Linked", $label="Social Media", $class="", $required=0, $order=1){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => $type,
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_order'=>$order,
            'field_is_required'=> $required,
        ));
        return $field->insert_into_db();   
    }

    public function create_template_file_upload_field($form_id,$page=1,$label="Upload File", $class="", $order=1, $format="PDF"){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'File',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_order'=>$order,
            'field_is_required'=> '',
            'field_value'=> $format
        ));
        return $field->insert_into_db();    
    }
    public function create_template_dob_field($form_id,$page=1,$label="Date Of Birth", $class="", $required="", $order=1){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Bdate',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_order'=>$order,
            'field_is_required'=> $required
        ));
        return $field->insert_into_db();    
    }
    public function create_template_calender_field($form_id,$page=1,$label="Date", $class="", $required=0, $order=1, $conditions=array()){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'jQueryUIDate',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_order'=>$order,
            'field_is_required'=> $required,
            'conditions'=>$conditions
        ));
        return $field->insert_into_db();    
    }
    public function create_template_esign_field($form_id,$page=1,$label="E-Sign", $class="", $required=0, $order=1){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'ESign',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_order'=>$order,
            'field_is_required'=> $required
        ));
        return $field->insert_into_db();    
    }
    public function create_template_richtext_field($form_id, $page=1, $label="rich text", $value="", $class="", $order=1){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'RichText',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_order'=>$order,
            'field_value'=>$value
        ));
        return $field->insert_into_db();  
    }
    public function create_template_price_field($form_id, $page=1, $label="Total Price",$order=1){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'PriceV',
            'page_no' => $page,
            'field_label' => $label,
            'field_order'=>$order
        ));
        return $field->insert_into_db();  
    }
    public function create_template_divider_field($form_id, $page=1, $label="divider", $class="", $order=1){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Divider',
            'page_no' => $page,
            'field_label' => $label,
            'field_css_class' => $class,
            'field_order'=>$order
        ));
        return $field->insert_into_db();  
    }
    
    public function create_template_repeatable_field($form_id, $page=1, $label="Repeatable", $required=0, $order=1){
        $field = new RM_Fields;
        $field->set(array('form_id' => $form_id,
            'field_type' => 'Repeatable',
            'page_no' => $page,
            'field_label' => $label,
            'field_order'=>$order,
            'field_is_required'=> $required
        ));
        return $field->insert_into_db(); 
    }
    public function create_dummy_product($product_name, $product_type, $product_value='100', $option_label=array(), $option_value=array()){
        if($product_type!='fixed'){
            $product_value ='';
        }
        $extra_options = array(
            'show_on_form'=>'yes',
            'allow_quantity'=>'yes',
            'min_quantity' => 0,
            'max_quantity' => 0
        );
        $results = RM_DBManager::get_all('PAYPAL_FIELDS', $offset=0, $limit=0, $column='*', $sort_by='', $descending=true);
        //if(count($results)){
            //$product_id = $results[0]->field_id;
        //}
        //else{
        $data = array(
            'type' => $product_type,
            'name' => $product_name,
            'value'=> $product_value,
            'class' => '',
            'option_label' => isset($option_label) ? maybe_serialize($option_label) : '',
            'option_price' => isset($option_value) ? maybe_serialize($option_value) : '',
            'option_value' => '',
            'description' => '',
            'require' => '',
            'order' =>'',
            'extra_options' => maybe_serialize($extra_options)
        );

        $data_specifiers = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%d','%s');

        $product_id = RM_DBManager::insert_row('PAYPAL_FIELDS', $data, $data_specifiers);
        //}
        return $product_id;
    }
}
