<?php
require_once 'sdk.class.php';
// Create the s3 Object from the SDK

echo "yo";

$s3 = new AmazonS3();
// Run the Method: Create Bucket.
// Arg 1: 'my-unique-bucket-name' is the name of your new bucket.
// Arg 2: The geographical region where your data will be stored.
// Arg 3: Tells Amazon to make your files readable by anyone like regular files hosted on a web server.

$response = $s3->create_bucket('stream_test_2013', AmazonS3::REGION_US_E1, AmazonS3::ACL_PUBLIC);

// The response comes back as a Simple XML Object
// In this case we just want to know if everything was okay.
// If not, report the message from the XML response.

if ((int) $response->isOK()) {
    echo 'Created Bucket';
} else {
    echo (string) $response->body->Message;
}


// $response = $s3->list_buckets();
// foreach ($response->body->Buckets[0] as $bucket) {
//     echo (string) $bucket->Name.&quot;<br />&quot;;
// }

?>