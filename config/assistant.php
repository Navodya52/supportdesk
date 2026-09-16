<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SupportDesk Assistant Knowledge Base
    |--------------------------------------------------------------------------
    |
    | Each entry maps an array of keywords to a response. The assistant scans
    | the user's message for these keywords (case-insensitive) and returns the
    | first matching response. The fallback is returned when no keywords match.
    |
    */

    'knowledge_base' => [
        [
            'keywords' => ['vpn', 'virtual private network', 'remote access', 'cannot connect vpn', 'vpn not working'],
            'topic' => 'VPN Issues',
            'response' => "**VPN Troubleshooting Steps:**\n\n1. Ensure you're connected to the internet before launching the VPN client.\n2. Open the VPN application and click **Connect** — wait up to 30 seconds.\n3. If it fails, try disconnecting and reconnecting.\n4. Restart the VPN client and try again.\n5. On Windows, go to **Settings → Network → VPN** and check the connection status.\n6. If you recently changed your password, update it inside the VPN client too.\n7. Restart your computer and attempt the connection once more.\n\nIf the issue persists after these steps, please create a support ticket for further assistance.",
        ],
        [
            'keywords' => ['password', 'reset password', 'forgot password', 'change password', 'locked out', 'account locked', 'cant login', 'cannot login', 'login failed'],
            'topic' => 'Password Reset',
            'response' => "**Password Reset Steps:**\n\n1. Go to the login page and click **Forgot your password?**.\n2. Enter your company email address and submit.\n3. Check your email inbox (and spam folder) for a reset link.\n4. Click the link and choose a new strong password (min. 8 characters, include numbers and symbols).\n5. If your account is locked, wait 15 minutes and try again — accounts auto-unlock after multiple failed attempts.\n6. If you do not receive the email within 5 minutes, check your spam/junk folder.\n\nStill having trouble? Create a support ticket and an agent will assist you shortly.",
        ],
        [
            'keywords' => ['network', 'internet', 'wifi', 'wi-fi', 'no internet', 'slow internet', 'connection', 'ethernet', 'cable', 'disconnected'],
            'topic' => 'Network / Internet',
            'response' => "**Network & Internet Troubleshooting:**\n\n1. Check that your network cable is firmly plugged in, or that you're connected to the correct Wi-Fi network.\n2. Try opening a different website to confirm whether it's a general internet issue.\n3. Restart your router/switch by unplugging it for 10 seconds, then plugging it back in.\n4. On Windows, run the **Network Troubleshooter**: Settings → System → Troubleshoot → Internet Connections.\n5. Flush your DNS cache: open Command Prompt as Administrator and run `ipconfig /flushdns`.\n6. Disable and re-enable your network adapter: right-click your network icon → **Troubleshoot problems**.\n7. If only certain sites are blocked, this may be a firewall restriction — contact IT.\n\nIf the problem continues, please raise a support ticket.",
        ],
        [
            'keywords' => ['email', 'outlook', 'mail', 'inbox', 'sending email', 'receiving email', 'email not working', 'cannot send', 'cannot receive'],
            'topic' => 'Email Issues',
            'response' => "**Email Troubleshooting Steps:**\n\n1. Check your internet connection first — email requires a working connection.\n2. Restart Outlook (or your email client) completely.\n3. Verify that your mailbox is not full — delete old emails or empty the trash/junk folder.\n4. If Outlook shows **Working Offline**, click **Send/Receive → Work Offline** to toggle it off.\n5. Check your **Outbox** folder — stuck emails can block sending.\n6. Try accessing your email via **Outlook Web App (OWA)** in a browser to isolate the issue.\n7. Clear your Outlook cache: Close Outlook, delete the OST/PST cache file, and reopen.\n\nIf you still cannot send or receive emails, please create a support ticket.",
        ],
        [
            'keywords' => ['slow', 'slow computer', 'laptop slow', 'pc slow', 'performance', 'freezing', 'hanging', 'not responding', 'laggy', 'lagging'],
            'topic' => 'Slow Computer',
            'response' => "**Computer Performance Troubleshooting:**\n\n1. Restart your computer — this clears temporary files and frees up memory.\n2. Close unnecessary applications and browser tabs that consume RAM.\n3. Check **Task Manager** (Ctrl+Shift+Esc) → Processes tab to find apps using excessive CPU or memory.\n4. Run a **Disk Cleanup**: Search for 'Disk Cleanup' in the Start menu → select your C: drive.\n5. Ensure Windows Updates are not running in the background (Settings → Windows Update).\n6. Check available disk space — if C: drive has less than 10% free, performance degrades significantly.\n7. Run a virus/malware scan using your company's antivirus software.\n8. Disable startup programs: Task Manager → Startup tab → disable non-essential items.\n\nIf performance is still poor after these steps, please raise a support ticket.",
        ],
        [
            'keywords' => ['software', 'install', 'installation', 'application', 'program', 'app', 'setup', 'cant install', 'cannot install', 'update software'],
            'topic' => 'Software Installation',
            'response' => "**Software Installation Guidance:**\n\n1. Only install software that is approved by your IT department — unauthorised software may violate company policy.\n2. If you require a new application, submit a software request through the support ticket system.\n3. For approved software, run the installer as Administrator: right-click the installer → **Run as administrator**.\n4. Ensure you have sufficient disk space before installing (at least 2x the installer size).\n5. Temporarily disable antivirus during installation if it blocks the setup, then re-enable it immediately after.\n6. If an update fails, try uninstalling the current version first, then install the new version.\n7. After installation, restart your computer to ensure changes take effect.\n\nFor software requiring a licence key or specific configuration, please create a support ticket.",
        ],
        [
            'keywords' => ['printer', 'print', 'printing', 'scanner', 'scan', 'cannot print', 'printer offline', 'print queue'],
            'topic' => 'Printer Issues',
            'response' => "**Printer Troubleshooting Steps:**\n\n1. Check that the printer is powered on and showing a **Ready** status on its display.\n2. Ensure the printer is connected to the network or via USB cable.\n3. On Windows, go to **Settings → Bluetooth & devices → Printers & scanners** — check if your printer shows as **Offline**. Right-click it → **See what's printing** → **Printer menu → Use Printer Online**.\n4. Clear the print queue: open the print queue and cancel all pending jobs.\n5. Restart the **Print Spooler** service: open Services (services.msc), find Print Spooler, and restart it.\n6. Try printing a test page directly from the printer's control panel.\n7. Reinstall the printer driver if the above steps fail.\n\nIf printing still fails, please create a support ticket.",
        ],
        [
            'keywords' => ['monitor', 'screen', 'display', 'no display', 'black screen', 'resolution', 'flickering', 'second monitor', 'dual monitor'],
            'topic' => 'Monitor / Display',
            'response' => "**Monitor & Display Troubleshooting:**\n\n1. Check that the monitor cable (HDMI/DisplayPort/VGA) is securely connected at both ends.\n2. Try a different cable or port if available.\n3. Ensure the monitor is powered on and set to the correct input source.\n4. Right-click the Desktop → **Display settings** to check resolution and arrangement for multiple monitors.\n5. Press **Windows key + P** to switch between display modes (PC screen only, Extend, Duplicate, Second screen only).\n6. Update or roll back display drivers via **Device Manager → Display adapters**.\n7. For a black screen on startup, connect an external monitor to determine if the issue is the screen or the GPU.\n\nIf none of these steps resolve the issue, please raise a support ticket.",
        ],
        [
            'keywords' => ['ticket', 'raise ticket', 'create ticket', 'support ticket', 'new ticket', 'report issue', 'log ticket'],
            'topic' => 'Creating a Support Ticket',
            'response' => "**How to Create a Support Ticket:**\n\nYou can submit a new support ticket directly from SupportDesk.\n\n1. Click **New Ticket** in the navigation sidebar, or use the button below.\n2. Fill in a clear, descriptive title.\n3. Select the appropriate category.\n4. Choose the priority level.\n5. Describe the issue in detail — include any error messages, screenshots, or steps to reproduce.\n6. Submit the form — you will receive updates as an agent works on your ticket.",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Quick Action Suggestions
    |--------------------------------------------------------------------------
    |
    | Displayed as clickable chips when the assistant panel first opens.
    |
    */

    'quick_actions' => [
        'VPN not working',
        'Password reset',
        'Email issues',
        'Network / internet',
        'Software installation',
        'Slow computer',
        'Printer not working',
        'Create a support ticket',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback Response
    |--------------------------------------------------------------------------
    */

    'fallback' => "I'm sorry, I don't have a specific guide for that issue yet. Here are a few things you can try:\n\n- Restart the application or your computer.\n- Check that your internet connection is working.\n- Look for any error messages and note them down.\n\nIf the problem continues, please **create a support ticket** and one of our agents will be happy to assist you.",

];
