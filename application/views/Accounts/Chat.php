<style>
  /* Chat container */
  .chat-container {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    height: 90%;
    max-width: 800px;
    margin: auto;
    padding: 20px;
    margin-top: 70px;
  }

  /* Chat messages */
  .chat-messages {
    width: 100%;
    overflow-y: auto;
    padding: 10px;
  }

  /* Message bubble */
  .message {
    margin-bottom: 10px;
    display: flex;
    flex-direction: column;
    word-wrap: break-word;
  }

  /* Sender's message */
  .sender {
    align-self: flex-end;
  }

  /* Receiver's message */
  .receiver {
    align-self: flex-start;
  }

  /* Message content */
  .message-content {
    background-color: #007bff;
    /* Blue color for sender messages */
    color: white;
    padding: 10px;
    border-radius: 10px;
    max-width: 70%;
  }

  /* Chat input */
  .chat-input {
    width: 100%;
    display: flex;
    align-items: center;
    padding: 10px;
  }

  .chat-input textarea {
    flex: 1;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: vertical;
    /* Allow vertical resizing */
    min-height: 50px;
    /* Set minimum height */
    max-height: 200px;
    /* Set maximum height */
    overflow-y: auto;
    /* Add vertical scrollbar when content exceeds max height */
  }

  /* Send button */
  .chat-input button {
    padding: 8px 16px;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    margin-left: 10px;
    /* Add space between textarea and button */
  }

  /* Sender's message */
  /* Sender's message */
  .sender .message-content {
    background-color: #007bff;
    /* Blue color for sender message content */
    color: white;
    /* Text color for sender message content */
    align-self: flex-end;
    /* Align sender message to the right */

  }

  /* Sender's name and date */
  .sender .message-name,
  .sender .message-date {
    align-self: flex-end;
    /* Align sender name and date to the right */
    margin-bottom: 5px;
    /* Margin to separate sender name and date from message content */
  }


  /* Receiver's message */
  .receiver .message-content {
    background-color: #f4f4f4;
    /* Light gray color for receiver messages */
    color: black;
    align-self: flex-start;

  }

  .receiver .message-name,
  .receiver .message-date {
    align-self: flex-start;
    /* Align sender name and date to the right */
    margin-bottom: 5px;
    /* Margin to separate sender name and date from message content */
  }

  /* Customize the scrollbar */
  .chat-messages {
    overflow-y: auto;
    /* Ensure scrollbar appears only when needed */
  }

  /* Track */
  ::-webkit-scrollbar {
    width: 12px;
    /* Width of the scrollbar */
  }

  /* Handle */
  ::-webkit-scrollbar-thumb {
    background: #888;
    /* Color of the scrollbar handle */
    border-radius: 6px;
    /* Rounded corners of the scrollbar handle */
  }

  /* Handle on hover */
  ::-webkit-scrollbar-thumb:hover {
    background: #555;
    /* Color of the scrollbar handle on hover */
  }

  /* Track */
  ::-webkit-scrollbar-track {
    background: #f1f1f1;
    /* Color of the scrollbar track */
  }

  /* Handle when scrolling */
  ::-webkit-scrollbar-thumb:active {
    background: #000;
    /* Color of the scrollbar handle when scrolling */
  }
</style>
<div class="chat-container">



  <?php if (empty($chat)) : ?>
    <div class="chat-messages">
      <p style="text-align:center">No messages found.</p>
    </div>
  <?php else : ?>
    <div class="chat-messages" id="chatMessages">
      <?php foreach ($chat as $message) : ?>
        <div class="message <?php echo $message->user === $_SESSION["user"]["username"] ? 'sender' : 'receiver'; ?>">
          <div class="message-name"><b><?php echo strtoupper($message->user); ?></b></div>
          <div class="message-content">
            <?php echo nl2br($message->message); ?>
            <?php if ($message->user === $_SESSION["user"]["username"]) : ?>
              <a href="<?php echo base_url('Accounts/deleteMessage/') . $message->id; ?>" onclick="return confirm('Are you sure you want to delete this message?')">
                <i class="fa fa-trash" style="color: red;"></i>
              </a>

            <?php endif; ?>
          </div>
          <div class="message-date"><?php echo date('d/m/Y h:i:s A', strtotime($message->created_at)); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <div class="chat-input">
    <form id="messageForm" class="chat-input" action="<?php echo base_url('Accounts/send_message'); ?>" method="post">
      <!-- Hidden input fields for raza_id and user -->
      <input type="hidden" name="raza_id" value="<?php echo $id; ?>">
      <input type="hidden" name="user" value="<?php echo $_SESSION['user']['username']; ?>">

      <!-- Textarea for the message content -->
      <textarea name="message" placeholder="Type your message..." rows="1"></textarea>

      <!-- Submit button to send the message -->
      <button type="button" onclick="sendMessage()">Send</button>
    </form>
  </div>
</div>
<script>
  function scrollToBottom() {
    var chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  // Call scrollToBottom function after loading messages
  scrollToBottom();

  function sendMessage() {
    // Get the form element
    var form = document.getElementById('messageForm');

    // Submit the form
    form.submit();
    scrollToBottom();
  }
</script>