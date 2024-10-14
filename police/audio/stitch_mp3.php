<?php
/**
 * stitch_mp3.php - Append an MP3 file to another MP3. Combine MP3 files together.
 */


class stitch_mp3
{
    
    /**
     * If the output is to be downloaded
     *          0 - Download
     *          1 - For player (Provide the headers as MP3: To be used as a source in mp3 player)
     */
    public $inline = 1;
    
    // The original file we are starting with
    public $contents = null;
    
    /**
     * function stitch_mp3( void )
     * 
     *  Constructor function for the class
     */
    
    function stitch_mp3($file)
    {
        // Retrieving the contents of the original file
        $this->contents = file_get_contents($file);
    }
    
    /**
     * public function append_mp3( string $orig, string|array $new )
     *      @string $orig - The contents of the original file... the files we are appending to.
     *      @strig $new - The filename of the file we are adding to the original
     *      @array $new - Filenames of multiple files we are adding to the original
     * 
     *  Adding contents of new file(s) to the original file (mp3 file)
     */
    
    public function append_mp3($new)
    {
        // Checking if the file(s) to be appended is provided in a string or an array (array for multiple files)
        if(is_array($new))
        {
            // Is an array, so we go the recursive rout (each key should hold a valid filename)
            foreach($new as $new_file)
            {
                // Checking if the file exists
                if(file_exists($new_file))
                {
                    // It exists, appending the file
                    $orig .= $this->append_mp3($new_file);
                }
            }
        }
        
        // Checking if it's a string (not an else, in case it's neither a string or an array)
        if(is_string($new))
        {
            // Retrieving the contents of the new file
            $contents = file_get_contents($new);
            
            // Removing the ID3 tags
            $contents = $this->clean_tags($contents);
            
            // Merging the new contents with the old
            $this->contents .= $contents;
        }
    }
    
    /**
     * private function start( string $contents )
     *      @string $contents - The contents of the mp3 file
     * 
     *  Finding the beginning of the actual MP3 amidst the ID3 tags
     */
    
    private function start($contents)
    {
		
        // The length of the MP3 file
        $strlen = strlen($contents);
        
        // Looping through the contents, looking for the first glimpse of MP3
        for($i = 0; $i < $strlen; $i++)
        {
            // Going through each character.
            $v = substr($contents, $i, 1);
            
            // Getting an ASCII representation of the character
            $value = ord($v);
            
            // We are looking for a character that has the ASCII representation of 255 (y with two dots on top)
            if($value == 255)
            {
                // Returning the desired character location
                return $i;
            }
        }
    }
    
    /**
     * private function end( string $contents )
     *      @string $contents - The contents of the MP3 file
     * 
     *  Calculating the end of the MP3 file
     */
    
    private function end($contents)
    {
        // Getting the length of the contents of the MP3 file
        $strlen = strlen($contents);
        
        // Subtracting things
        $str = substr($contents, ($strlen - 128));
        $str1 = substr($str, 0, 3);
        
        // Checking if the retrieved line of code is 'tag'
        if(strtolower($str1) == 'tag')
        {
            // Return the cleaned content
            return $str;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * private function clean_tags( string $contents )
     *      @string $contents - The contents of the MP3 file
     * 
     *  Function to clean the ID3 tags from the MP3 file
     */
    
    private function clean_tags($contents)
    {
        // Get the location of the beginning of the actual MP3 sound
        $s = $start = $this->start($contents);
        
        // Checking if it went successful or not
        if($s === false)
        {
            return false;
        }
        else
        {
            // It was successful, remove the ID3 and leave the sound info
            $contents = substr($contents, $start);
        }
        
        // Getting the location of the end of MP3 sound
        if($this->end($contents) !== false)
        {
            // If it was successful, remove the last bits of junk
            $contents = substr($contents, 0, (strlen($contents) - 129));
        }
        
        // Returning the content cleaned from ID3 tags
        return $contents;
    }
    
    /**
     * public function output( string $file_name )
     *      @string $file_name - The name of the file
     */
    
    public function output($file_name)
    {
        //Output mp3
        //Send to standard output
        if(ob_get_contents())
        {
            $this->error('Some data has already been output, can\'t send mp3 file');
        }
        
        // Checking for the type of interface we are in (need command line interface; or 'cli')
        if(php_sapi_name() != 'cli')
        {
            // Checking if the headers were already sent
            if(headers_sent())
            {
                // It was, not a good thing
                $this->error('Some data has already been output to browser, can\'t send mp3 file');
            }
            
            // We send it to the browser
            header('Content-Type: audio/mpeg3');
            
            // To download the stitched creation
            if(!$this->inline)
            {
                // Sending the headers to download the file
                header('Content-Length: ' . strlen($this->contents));
                header('Content-Disposition: attachment; filename="' . $file_name . '"');
            }
            
            // To play it inline with the browser
            if($this->inline)
            {
                // Sending the headers to play the file inline with the browser
                header('Content-Type: audio/mpeg');
                header('Content-Length: ' . strlen($this->contents));
                header('Content-Disposition: inline;');
                header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
                header("Cache-Control: post-check=0, pre-check=0", false);
                header("Pragma: no-cache");
                header("Content-Transfer-Encoding: binary");
            }
        }
        
        // Printing the contents of the created MP3 file
        echo $this->contents;
        
        // Returning a blank/empty string
        return '';
    }
}

?>