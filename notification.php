<?php
// notification_system.php - Complete Implementation
require_once 'database.php';

class NotificationSystem {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    // ==========================================
    // 1. APPLICATION SUBMISSION NOTIFICATION
    // ==========================================
    public function notifyApplicationSubmitted($user_id, $reference_number) {
        $title = "✅ Application Submitted Successfully";
        $message = "Your renewal application has been submitted successfully. Reference Number: {$reference_number}. We'll notify you once your application is under review.";
        
        // Create in-app notification
        $this->createInAppNotification($user_id, $title, $message, 'success');
        
        // Send email
        $this->sendEmailNotification(
            $user_id, 
            "Application Submitted - NRB System",
            "We've received your ID renewal application with reference number: {$reference_number}. You can track your application status anytime by logging into your account.",
            $reference_number
        );
        
        return true;
    }
    
    // ==========================================
    // 2. APPLICATION UNDER REVIEW NOTIFICATION
    // ==========================================
    public function notifyApplicationUnderReview($user_id, $reference_number) {
        $title = "👀 Application Under Review";
        $message = "Your application (Ref: {$reference_number}) is now being verified by NRB officers. This process typically takes 2-3 business days.";
        
        // Create in-app notification
        $this->createInAppNotification($user_id, $title, $message, 'info');
        
        // Optional: Send email
        $this->sendEmailNotification(
            $user_id,
            "Application Under Review - NRB System",
            "Your ID renewal application (Ref: {$reference_number}) is currently under review by our verification team. We will notify you once the review is complete.",
            $reference_number
        );
        
        return true;
    }
    
    // ==========================================
    // 3. APPLICATION APPROVED NOTIFICATION
    // ==========================================
    public function notifyApplicationApproved($user_id, $reference_number) {
        $title = "✅ Application Approved!";
        $message = "Great news! Your application (Ref: {$reference_number}) has been approved and is now being processed. Your new ID will be ready for collection soon.";
        
        // Create in-app notification
        $this->createInAppNotification($user_id, $title, $message, 'success');
        
        // Send email
        $this->sendEmailNotification(
            $user_id,
            "Application Approved - NRB System",
            "Your ID renewal application has been approved! Your application (Ref: {$reference_number}) is now in the production queue. You'll receive another notification when your ID is ready for collection.",
            $reference_number
        );
        
        return true;
    }
    
    // ==========================================
    // 4. APPLICATION REJECTED NOTIFICATION
    // ==========================================
    public function notifyApplicationRejected($user_id, $reference_number, $reason) {
        $title = "⚠️ Application Rejected";
        $message = "Unfortunately, your application (Ref: {$reference_number}) was rejected. Reason: {$reason}. Please review the feedback and submit a new application with the required documents.";
        
        // Create in-app notification
        $this->createInAppNotification($user_id, $title, $message, 'warning');
        
        // Send email
        $this->sendEmailNotification(
            $user_id,
            "Application Rejected - NRB System",
            "Unfortunately, your application (Ref: {$reference_number}) was rejected. Reason: {$reason}. Please update your application with the necessary documents and resubmit. If you need assistance, please visit any NRB office or contact our support team.",
            $reference_number
        );
        
        return true;
    }
    
    // ==========================================
    // 5. ID READY FOR COLLECTION NOTIFICATION
    // ==========================================
    public function notifyIdReadyForCollection($user_id, $reference_number, $collection_center) {
        $title = "🎉 ID Ready for Collection!";
        $message = "Your new National ID is ready for collection at {$collection_center}. Please collect it within 14 days. Bring your reference number ({$reference_number}) and any original documents. Collection hours: 8:00 AM - 5:00 PM, Monday to Friday.";
        
        // Create in-app notification
        $this->createInAppNotification($user_id, $title, $message, 'success');
        
        // Send email
        $this->sendEmailNotification(
            $user_id,
            "ID Ready for Collection - NRB System",
            "Your ID is ready! Your new National ID (Ref: {$reference_number}) is ready for collection at {$collection_center}. Please collect it within 14 days to avoid any delays. Remember to bring:\n- Your reference number: {$reference_number}\n- Original documents submitted\n- Valid identification\n\nCollection Hours:\n8:00 AM - 5:00 PM\nMonday to Friday",
            $reference_number
        );
        
        return true;
    }
    
    // ==========================================
    // HELPER FUNCTIONS
    // ==========================================
    
    private function createInAppNotification($user_id, $title, $message, $type) {
        try {
            $query = "INSERT INTO notifications (user_id, title, message, type, is_read, created_at) 
                      VALUES (:user_id, :title, :message, :type, 0, NOW())";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':type', $type);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Create notification failed: " . $e->getMessage());
            return false;
        }
    }
    
    private function sendEmailNotification($user_id, $subject, $body, $reference_number) {
        try {
            // Get user email
            $user = $this->getUserInfo($user_id);
            if (!$user) {
                return false;
            }
            
            $to = $user['email'];
            $name = $user['full_name'];
            
            // Generate email HTML
            $emailHtml = $this->generateEmailTemplate($name, $subject, $body, $reference_number);
            
            // Email headers
            $headers = [
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=utf-8',
                'From: NRB System <noreply@nrb.gov.mw>',
                'Reply-To: support@nrb.gov.mw',
                'X-Mailer: PHP/' . phpversion()
            ];
            
            // Send email
            $success = mail($to, $subject, $emailHtml, implode("\r\n", $headers));
            
            // Log email attempt
            $this->logEmail($user_id, $to, $subject, $success);
            
            return $success;
        } catch (Exception $e) {
            error_log("Send email failed: " . $e->getMessage());
            return false;
        }
    }
    
    private function getUserInfo($user_id) {
        try {
            $query = "SELECT email, full_name FROM users WHERE id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Get user info failed: " . $e->getMessage());
            return false;
        }
    }
    
    private function generateEmailTemplate($name, $subject, $body, $reference_number) {
        $refSection = $reference_number ? "<p style='background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 20px 0;'><strong>Reference Number:</strong> <span style='font-family: monospace; font-size: 1.1rem; color: #2c5aa0;'>$reference_number</span></p>" : "";
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4;'>
            <div style='max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);'>
                <div style='background: linear-gradient(135deg, #2c5aa0 0%, #1e4080 100%); color: white; padding: 30px 20px; text-align: center;'>
                    <h1 style='margin: 0; font-size: 28px;'>National Registration Bureau</h1>
                    <p style='margin: 10px 0 0; opacity: 0.9; font-size: 14px;'>Digital ID Services</p>
                </div>
                
                <div style='padding: 30px 20px;'>
                    <h2 style='color: #2c5aa0; margin-top: 0;'>Hello, $name!</h2>
                    
                    $refSection
                    
                    <div style='background: #f8f9ff; border-left: 4px solid #2c5aa0; padding: 20px; margin: 20px 0; border-radius: 0 8px 8px 0;'>
                        <p style='margin: 0; line-height: 1.6;'>$body</p>
                    </div>
                    
                    <div style='background: #e8f5e8; border: 1px solid #c3e6c3; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                        <h4 style='margin: 0 0 10px; color: #2e7d2e;'>Need Help?</h4>
                        <p style='margin: 0; font-size: 14px;'>
                            <strong>Call:</strong> +265-1-770-411<br>
                            <strong>Email:</strong> support@nrb.gov.mw<br>
                            <strong>Visit:</strong> Any NRB office nationwide
                        </p>
                    </div>
                    
                    <p style='margin: 20px 0; font-size: 14px; color: #666;'>
                        Track your application anytime by <a href='http://localhost/nrb_system/login.php' style='color: #2c5aa0; text-decoration: none;'>logging into your account</a>.
                    </p>
                </div>
                
                <div style='background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef;'>
                    <p style='margin: 0; font-size: 12px; color: #6c757d;'>
                        This is an automated message. Please do not reply to this email.<br>
                        For support, use the contact information above.
                    </p>
                    <p style='margin: 10px 0 0; font-size: 11px; color: #adb5bd;'>
                        &copy; " . date('Y') . " National Registration Bureau, Malawi. All rights reserved.
                    </p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function logEmail($user_id, $email, $subject, $success) {
        try {
            $query = "INSERT INTO email_logs (user_id, email, subject, success, created_at) 
                      VALUES (:user_id, :email, :subject, :success, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':success', $success ? 1 : 0);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Log email failed: " . $e->getMessage());
        }
    }
}

// ==========================================
// USAGE EXAMPLES
// ==========================================

/*
// Example 1: When citizen submits application
require_once 'notification_system.php';

$notifier = new NotificationSystem();
$user_id = $_SESSION['user_id']; // From session
$reference_number = 'NRB-2025-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

$notifier->notifyApplicationSubmitted($user_id, $reference_number);


// Example 2: When admin marks as under review
$notifier->notifyApplicationUnderReview($user_id, $reference_number);


// Example 3: When admin approves application
$notifier->notifyApplicationApproved($user_id, $reference_number);


// Example 4: When admin rejects application
$reason = "Missing police report";
$notifier->notifyApplicationRejected($user_id, $reference_number, $reason);


// Example 5: When ID is ready for collection
$collection_center = "NRB Blantyre Office";
$notifier->notifyIdReadyForCollection($user_id, $reference_number, $collection_center);
*/

?>