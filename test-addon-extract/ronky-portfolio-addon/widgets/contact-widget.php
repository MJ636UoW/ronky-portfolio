<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_GenZ_Contact_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'genz-contact-widget';
	}

	public function get_title() {
		return esc_html__( 'Genz Contact & Integrations', 'genz-portfolio-addon' );
	}

	public function get_icon() {
		return 'eicon-mail';
	}

	public function get_categories() {
		return [ 'genz-portfolio' ];
	}

	public function get_script_depends() {
		return [ 'ronky-portfolio-scripts' ];
	}

	public function get_style_depends() {
		return [ 'ronky-portfolio-styles' ];
	}

	protected function register_controls() {
		// Content Info
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Contact Headline & Text', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Headline', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'LET\'S MAKE<br>SOMETHING<br><span class="accent-text text-glow">UNREAL.</span>', 'genz-portfolio-addon' ),
			]
		);

		$this->add_control(
			'description',
			[
				'label' => esc_html__( 'Description Text', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Book a shoot, propose an editorial collaboration, or just ask a question.', 'genz-portfolio-addon' ),
			]
		);

		$this->end_controls_section();

		// Social Integrations
		$this->start_controls_section(
			'integrations_section',
			[
				'label' => esc_html__( 'Social & Contact Channels', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'channel_label',
			[
				'label' => esc_html__( 'Channel Type (e.g. EMAIL, INSTAGRAM)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'EMAIL',
			]
		);

		$repeater->add_control(
			'channel_value',
			[
				'label' => esc_html__( 'Channel Display Value', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'hello@kai.studio',
			]
		);

		$repeater->add_control(
			'channel_link',
			[
				'label' => esc_html__( 'Channel Action Link', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'mailto: or https://', 'genz-portfolio-addon' ),
			]
		);

		$repeater->add_control(
			'channel_icon_char',
			[
				'label' => esc_html__( 'Display Emoji Icon', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '✉',
			]
		);

		$this->add_control(
			'channels_list',
			[
				'label' => esc_html__( 'Integrations Channels', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'channel_label' => 'EMAIL', 'channel_value' => 'hello@kai.studio', 'channel_icon_char' => '✉' ],
					[ 'channel_label' => 'WHATSAPP', 'channel_value' => '+1 (555) 019-2026', 'channel_icon_char' => '💬' ],
					[ 'channel_label' => 'INSTAGRAM', 'channel_value' => '@kai.visions', 'channel_icon_char' => '📸' ]
				],
				'title_field' => '{{{ channel_label }}}: {{{ channel_value }}}',
			]
		);

		$this->end_controls_section();

		// Fluent Form ID configuration
		$this->start_controls_section(
			'form_integration_section',
			[
				'label' => esc_html__( 'Fluent Form Connection', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'fluent_form_id',
			[
				'label' => esc_html__( 'Fluent Form ID', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '1',
				'description' => esc_html__( 'Specify the Fluent Form ID to connect this contact form to.', 'genz-portfolio-addon' ),
			]
		);

		$this->end_controls_section();

		// Native Form & Email Settings
		$this->start_controls_section(
			'native_form_section',
			[
				'label' => esc_html__( 'Native Form & Email Settings', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'recipient_email',
			[
				'label' => esc_html__( 'Notification Recipient Email', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'client@example.com', 'genz-portfolio-addon' ),
				'description' => esc_html__( 'The email address that will receive form notifications. Falls back to site admin email or Customizer email if blank.', 'genz-portfolio-addon' ),
			]
		);

		$this->add_control(
			'email_subject',
			[
				'label' => esc_html__( 'Email Subject', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'New Contact Submission from {name}', 'genz-portfolio-addon' ),
			]
		);

		$this->add_control(
			'email_body',
			[
				'label' => esc_html__( 'Email Message Template', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( "You have a new contact form submission:\n\nName: {name}\nEmail: {email}\nPhone: {phone}\nService: {service}\nBudget: {budget}\n\nMessage:\n{message}", 'genz-portfolio-addon' ),
				'description' => esc_html__( 'Support placeholders: {name}, {email}, {phone}, {service}, {budget}, {message}', 'genz-portfolio-addon' ),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<section class="wp-contact-section">
			<div class="contact-grid">
				
				<div class="contact-info-block">
					<h2 class="section-title"><?php echo wp_kses_post( htmlspecialchars_decode( $settings['title'] ) ); ?></h2>
					<p class="contact-desc"><?php echo esc_html( $settings['description'] ); ?></p>
					
					<div class="contact-integrations">
						<?php foreach ( $settings['channels_list'] as $item ) : 
							$link_url = ! empty( $item['channel_link']['url'] ) ? $item['channel_link']['url'] : '#';
						?>
							<a href="<?php echo esc_url( $link_url ); ?>" class="integration-item magnetic" data-magnetic-strength="0.3">
								<span class="integration-icon"><?php echo esc_html( $item['channel_icon_char'] ); ?></span>
								<div class="integration-text">
									<div class="integration-label"><?php echo esc_html( $item['channel_label'] ); ?></div>
									<div class="integration-val"><?php echo esc_html( $item['channel_value'] ); ?></div>
								</div>
							</a>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="contact-form-block glass-card">
					<form class="modern-form wp-portfolio-form" data-fluent-form-id="<?php echo esc_attr( $settings['fluent_form_id'] ); ?>">
						<input type="hidden" name="page_id" value="<?php echo esc_attr( get_the_ID() ); ?>" />
						<input type="hidden" name="element_id" value="<?php echo esc_attr( $this->get_id() ); ?>" />
						
						<div class="form-row-2">
							<div class="form-group">
								<input type="text" id="wp-form-name" name="name" required placeholder=" " />
								<label for="wp-form-name">Full Name</label>
								<span class="input-line"></span>
							</div>
							<div class="form-group">
								<input type="email" id="wp-form-email" name="email" required placeholder=" " />
								<label for="wp-form-email">Email Address</label>
								<span class="input-line"></span>
							</div>
						</div>

						<div class="form-row-2">
							<div class="form-group">
								<input type="tel" id="wp-form-phone" name="phone" placeholder=" " />
								<label for="wp-form-phone">Phone (Optional)</label>
								<span class="input-line"></span>
							</div>
							<div class="form-group">
								<select id="wp-form-service" name="service" required>
									<option value="" disabled selected hidden></option>
									<option value="photography">Photography Campaign</option>
									<option value="videography">Videography / Showreel</option>
									<option value="drone">FPV Drone Shoots</option>
									<option value="custom">Full Visual Direction</option>
								</select>
								<label for="wp-form-service">Service Needed</label>
								<span class="input-line"></span>
							</div>
						</div>

						<div class="form-group">
							<select id="wp-form-budget" name="budget" required>
								<option value="" disabled selected hidden></option>
								<option value="starter">$1,500 - $3,000</option>
								<option value="medium">$3,000 - $7,000</option>
								<option value="premium">$7,000 - $15,000</option>
								<option value="agency">$15,000+</option>
							</select>
							<label for="wp-form-budget">Project Budget</label>
							<span class="input-line"></span>
						</div>

						<div class="form-group">
							<textarea id="wp-form-message" name="message" required placeholder=" " rows="4"></textarea>
							<label for="wp-form-message">Tell us about your project</label>
							<span class="input-line"></span>
						</div>

						<button type="submit" class="btn-primary form-submit-btn magnetic" data-magnetic-strength="0.3">
							<span>SEND TRANSMISSION</span>
						</button>
					</form>
				</div>

			</div>
		</section>
		<?php
	}
}

